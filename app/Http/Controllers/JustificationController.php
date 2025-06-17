<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Justification;
use App\Models\UniversityClass;
use App\Models\JustificationDocument;
use App\Models\ClassGroup;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class JustificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Justification::with(['class.faculty', 'student', 'document'])
            ->where('student_id', auth()->id())
            ->when($request->filled('search'), function($q) use ($request) {
                $q->where(function($query) use ($request) {
                    $query->where('description', 'like', '%'.$request->search.'%')
                          ->orWhereHas('class', function($q) use ($request) {
                              $q->where('name', 'like', '%'.$request->search.'%');
                          });
                });
            })
            ->orderBy(
                $request->get('sort_by', 'start_date'),
                $request->get('sort_dir', 'desc')
            )
            ->paginate(
                $request->get('per_page', 15)
            )
            ->withQueryString();

        return view('justifications.index', [
            'justifications' => $query,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = UniversityClass::with('faculty')->get();
        return view('justifications.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'university_class_id' => [
                'required',
                'exists:classes,id',
                function ($attribute, $value, $fail) use ($request) {
                    $start = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    
                    $days = [];
                    for ($date = $start; $date->lte($end); $date->addDay()) {
                        $days[] = $date->dayOfWeek;
                    }
                    
                    $hasValidDays = ClassGroup::where('class_id', $value)
                        ->whereHas('days', function($q) use ($days) {
                            $q->whereIn('weekday', array_unique($days));
                        })->exists();
                    
                    if (!$hasValidDays) {
                        $fail('La clase seleccionada no tiene horarios en las fechas indicadas.');
                    }
                }
            ],
            'document' => 'required|file|max:2048|mimes:pdf,jpg,png'
        ]);

        DB::transaction(function () use ($data, $request) {
            $justification = Justification::create([
                'description' => $data['description'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'university_class_id' => $data['university_class_id'],
                'student_id' => auth()->id()
            ]);

            $file = $request->file('document');
            $path = $file->store('justifications', 'public');

            $justification->document()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize()
            ]);
        });

        return redirect()->route('justifications.index')
            ->with('alert', [
                'type' => 'success',
                'message' => 'Justificación creada exitosamente.'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Justification $justification)
    {
        if ($justification->student_id !== auth()->id()) {
            abort(403);
        }
        
        return view('justifications.show', [
            'justification' => $justification->load(['class.faculty', 'student', 'document'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Justification $justification)
    {
        if ($justification->student_id !== auth()->id()) {
            abort(403);
        }
        
        $classes = UniversityClass::with('faculty')->get();
        return view('justifications.edit', [
            'justification' => $justification,
            'classes' => $classes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Justification $justification)
    {
        if ($justification->student_id !== auth()->id()) {
            abort(403);
        }
        
        $data = $request->validate([
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'university_class_id' => [
                'required',
                'exists:classes,id',
                function ($attribute, $value, $fail) use ($request) {
                    $start = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    
                    $days = [];
                    for ($date = $start; $date->lte($end); $date->addDay()) {
                        $days[] = $date->dayOfWeek;
                    }
                    
                    $hasValidDays = ClassGroup::where('class_id', $value)
                        ->whereHas('days', function($q) use ($days) {
                            $q->whereIn('weekday', array_unique($days));
                        })->exists();
                    
                    if (!$hasValidDays) {
                        $fail('La clase seleccionada no tiene horarios en las fechas indicadas.');
                    }
                }
            ],
            'document' => 'sometimes|file|max:2048|mimes:pdf,jpg,png'
        ]);

        DB::transaction(function () use ($data, $request, $justification) {
            $justification->update([
                'description' => $data['description'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'university_class_id' => $data['university_class_id']
            ]);

            if ($request->hasFile('document')) {
                // Eliminar documento anterior si existe
                if ($justification->document) {
                    Storage::disk('public')->delete($justification->document->file_path);
                    $justification->document()->delete();
                }

                $file = $request->file('document');
                $path = $file->store('justifications', 'public');

                $justification->document()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
            }
        });

        return redirect()->route('justifications.index')
            ->with('alert', [
                'type' => 'success',
                'message' => 'Justificación actualizada exitosamente.'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Justification $justification)
    {
        if ($justification->student_id !== auth()->id()) {
            abort(403);
        }
        
        DB::transaction(function () use ($justification) {
            if ($justification->document) {
                Storage::disk('public')->delete($justification->document->file_path);
                $justification->document()->delete();
            }
            
            $justification->delete();
        });

        return response()->json(null, 204);
    }

    /**
     * Obtiene las clases disponibles para un día específico de la semana
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableClasses(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start = new \DateTime($validated['start_date']);
        $end = new \DateTime($validated['end_date']);
        $weekdays = [];
        while ($start <= $end) {
            $dow = (int)$start->format('w');
            if (!in_array($dow, $weekdays)) {
                $weekdays[] = $dow;
            }
            $start->modify('+1 day');
        }
        sort($weekdays);

        $classes = \App\Models\UniversityClass::with(['faculty', 'groups.days'])
            ->whereHas('groups.days', function ($q) use ($weekdays) {
                $q->whereIn('weekday', $weekdays);
            })
            ->get()
            ->filter(function ($class) use ($weekdays) {
                foreach ($class->groups as $group) {
                    $groupDays = $group->days->pluck('weekday')->unique()->sort()->values()->toArray();
                    // El grupo debe incluir al menos todos los días seleccionados
                    if (empty(array_diff($weekdays, $groupDays))) {
                        return true;
                    }
                }
                return false;
            })
            ->values();

        return response()->json($classes->toArray());
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Justification;
use Illuminate\Http\Request;

class JustificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Justification::with('user');

        if ($search = $request->input('search')) {
            $query->where('reason', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $justifications = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('justifications.index', compact('justifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('justifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
<<<<<<< HEAD
            'reason'     => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'reason.required' => 'La justificación es obligatoria.',
            'attachment.mimes' => 'El archivo debe ser una imagen o PDF.',
            'attachment.max' => 'El archivo no debe superar los 2MB.',
=======
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
>>>>>>> parent of 3a1f45f (Merge branch 'main' of https://github.com/ces-silv/Web-Design-Final-Project into feature/justifications)
        ]);

        $data['user_id'] = auth()->id();
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('justifications', 'public');
        }

        Justification::create($data);

        return redirect()->route('justifications.index')
            ->with('alert', [
                'type'    => 'success',
                'message' => 'Justificación enviada correctamente.'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Justification $justification)
    {
<<<<<<< HEAD
        return view('justifications.show', compact('justification'));
=======
        if ($justification->student_id !== auth()->id()) {
            abort(403);
        }
        
        return view('justifications.show', [
            'justification' => $justification->load(['class.faculty', 'student', 'document'])
        ]);
>>>>>>> parent of 3a1f45f (Merge branch 'main' of https://github.com/ces-silv/Web-Design-Final-Project into feature/justifications)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Justification $justification)
    {
<<<<<<< HEAD
        return view('justifications.edit', compact('justification'));
=======
        if ($justification->student_id !== auth()->id()) {
            abort(403);
        }
        
        $classes = UniversityClass::with('faculty')->get();
        return view('justifications.edit', [
            'justification' => $justification,
            'classes' => $classes
        ]);
>>>>>>> parent of 3a1f45f (Merge branch 'main' of https://github.com/ces-silv/Web-Design-Final-Project into feature/justifications)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Justification $justification)
    {
<<<<<<< HEAD
        $data = $request->validate([
            'reason'     => 'required|string',
            'status'     => 'required|in:pending,approved,rejected',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'reason.required' => 'La justificación es obligatoria.',
            'status.required' => 'El estado es obligatorio.',
            'attachment.mimes' => 'El archivo debe ser una imagen o PDF.',
            'attachment.max' => 'El archivo no debe superar los 2MB.',
=======
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
>>>>>>> parent of 3a1f45f (Merge branch 'main' of https://github.com/ces-silv/Web-Design-Final-Project into feature/justifications)
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('justifications', 'public');
        } else {
            unset($data['attachment']);
        }

        $justification->update($data);

        return redirect()->route('justifications.index')
            ->with('alert', [
                'type'    => 'success',
                'message' => 'Justificación actualizada correctamente.'
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
<<<<<<< HEAD
<<<<<<< HEAD
            'weekday' => 'required|integer|between:0,6'
        ]);

        $weekday = $validated['weekday'];

        $classes = UniversityClass::query()
            ->with([
                'faculty',
                'groups.days'
            ])
            ->whereHas('groups.days', function($query) use ($weekday) {
                $query->where('weekday', $weekday);
            })
            ->get()
            ->map(function ($class) use ($weekday) {
                $class->setRelation('groups', $class->groups->filter(function ($group) use ($weekday) {
                    $group->setRelation('days', $group->days->filter(function ($day) use ($weekday) {
                        return $day->weekday == $weekday;
                    }));
                    return $group->days->where('weekday', $weekday)->isNotEmpty();
                })->values());
                return $class;
            })
            ->filter(function ($class) {
                return $class->groups->isNotEmpty();
            })
=======
            'weekdays' => 'required|array',
            'weekdays.*' => 'integer|between:0,6'
=======
            'weekday' => 'required|integer|between:0,6'
>>>>>>> parent of 5a56ee9 (Days selection has been corrected)
        ]);

        $weekday = $validated['weekday'];

        $classes = UniversityClass::query()
            ->with([
                'faculty',
                'groups.days'
            ])
            ->whereHas('groups.days', function($query) use ($weekday) {
                $query->where('weekday', $weekday);
            })
            ->get()
            ->map(function ($class) use ($weekday) {
                $class->setRelation('groups', $class->groups->filter(function ($group) use ($weekday) {
                    $group->setRelation('days', $group->days->filter(function ($day) use ($weekday) {
                        return $day->weekday == $weekday;
                    }));
                    return $group->days->where('weekday', $weekday)->isNotEmpty();
                })->values());
                return $class;
            })
            ->filter(function ($class) {
                return $class->groups->isNotEmpty();
            })
>>>>>>> parent of 38d2976 (Show the classes from a date range)
            ->values();

        // Para asegurar JSON plano y sin problemas de colección
        return response()->json($classes->toArray());
<<<<<<< HEAD
   }
}
=======
    }
}
>>>>>>> parent of 3a1f45f (Merge branch 'main' of https://github.com/ces-silv/Web-Design-Final-Project into feature/justifications)

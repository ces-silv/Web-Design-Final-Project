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
        $query = Justification::with(['class.faculty', 'student', 'documents'])
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
                    for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                        $days[] = $date->dayOfWeek; // 0=domingo, 6=sábado (Carbon)
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
            'documents.*' => 'required|file|max:2048|mimes:pdf,jpg,png'
        ], [
            'documents.*.required' => 'Al menos un documento es obligatorio.',
            'documents.*.file' => 'El archivo debe ser válido.',
            'documents.*.max' => 'Cada documento no puede superar 2MB.',
            'documents.*.mimes' => 'Solo se permiten archivos PDF, JPG y PNG.'
        ]);

        DB::transaction(function () use ($data, $request) {
            $justification = Justification::create([
                'description' => $data['description'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'university_class_id' => $data['university_class_id'],
                'student_id' => auth()->id(),
                'status' => 'En Proceso'
            ]);

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('justifications', 'public');
                    
                    $justification->documents()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);
                }
            }
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
            'justification' => $justification->load(['class.faculty', 'student', 'documents'])
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
            'justification' => $justification->load(['class.faculty', 'student', 'documents']),
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
                    for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                        $days[] = $date->dayOfWeek; // 0=domingo, 6=sábado (Carbon)
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
            'documents.*' => 'sometimes|file|max:2048|mimes:pdf,jpg,png',
            'remove_documents' => 'sometimes|array',
            'remove_documents.*' => 'exists:justification_documents,id'
        ], [
            'documents.*.file' => 'El archivo debe ser válido.',
            'documents.*.max' => 'Cada documento no puede superar 2MB.',
            'documents.*.mimes' => 'Solo se permiten archivos PDF, JPG y PNG.'
        ]);

        DB::transaction(function () use ($data, $request, $justification) {
            $justification->update([
                'description' => $data['description'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'university_class_id' => $data['university_class_id']
            ]);

            // Eliminar documentos marcados para eliminar
            if ($request->has('remove_documents')) {
                foreach ($request->remove_documents as $documentId) {
                    $document = $justification->documents()->find($documentId);
                    if ($document) {
                        Storage::disk('public')->delete($document->file_path);
                        $document->delete();
                    }
                }
            }

            // Agregar nuevos documentos
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('justifications', 'public');
                    
                    $justification->documents()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);
                }
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
            // Eliminar todos los documentos asociados
            foreach ($justification->documents as $document) {
                Storage::disk('public')->delete($document->file_path);
            }
            $justification->documents()->delete();
            $justification->delete();
        });

        return redirect()->route('justifications.index')
            ->with('alert', [
                'type' => 'success',
                'message' => 'Justificación eliminada exitosamente.'
            ]);
    }

    /**
     * Download a specific document associated with a justification.
     */
    public function downloadDocument(Justification $justification, JustificationDocument $document)
    {
        // Verificar que el usuario autenticado sea el propietario de la justificación
        if ($justification->student_id !== auth()->id()) {
            abort(403, 'No tienes permisos para descargar este documento.');
        }

        // Verificar que el documento pertenece a la justificación
        if ($document->justification_id !== $justification->id) {
            abort(404, 'El documento no pertenece a esta justificación.');
        }

        // Verificar que el archivo existe en el storage
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'El archivo no existe en el servidor.');
        }

        // Descargar el archivo
        return Storage::disk('public')->download(
            $document->file_path,
            $document->file_name
        );
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
            ->values();

        // Para asegurar JSON plano y sin problemas de colección
        return response()->json($classes->toArray());
    }
}
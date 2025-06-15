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
            'reason'     => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'reason.required' => 'La justificación es obligatoria.',
            'attachment.mimes' => 'El archivo debe ser una imagen o PDF.',
            'attachment.max' => 'El archivo no debe superar los 2MB.',
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
        return view('justifications.show', compact('justification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Justification $justification)
    {
        return view('justifications.edit', compact('justification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Justification $justification)
    {
        $data = $request->validate([
            'reason'     => 'required|string',
            'status'     => 'required|in:pending,approved,rejected',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'reason.required' => 'La justificación es obligatoria.',
            'status.required' => 'El estado es obligatorio.',
            'attachment.mimes' => 'El archivo debe ser una imagen o PDF.',
            'attachment.max' => 'El archivo no debe superar los 2MB.',
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
        $justification->delete();
        return redirect()->route('justifications.index')
            ->with('alert', [
                'type'    => 'success',
                'message' => 'Justificación eliminada correctamente.'
            ]);
    }
}

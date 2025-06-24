<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index(Request $request)
    {
        $query = Faculty::query();

        // Búsqueda por nombre
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Ordenamiento
        if ($sort = $request->input('sort')) {
            match($sort) {
                'name_asc'  => $query->orderBy('name', 'asc'),
                'name_desc' => $query->orderBy('name', 'desc'),
                'newest'    => $query->orderBy('created_at', 'desc'),
                'oldest'    => $query->orderBy('created_at', 'asc'),
                default     => $query->orderBy('name', 'asc')
            };
        } else {
            // Ordenamiento por defecto
            $query->orderBy('name', 'asc');
        }

        $faculties = $query->paginate(10)->withQueryString();

        return view('faculties.index', compact('faculties'));
    }

    public function create()
    {
        return view('faculties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:faculties'
        ]);

        Faculty::create($request->all());

        return redirect()->route('faculties.index')
            ->with('success', 'Facultad creada exitosamente.');
    }

    public function edit(Faculty $faculty)
    {
        return view('faculties.edit', compact('faculty'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name,' . $faculty->id
        ]);

        $faculty->update($request->all());

        return redirect()->route('faculties.index')
            ->with('success', 'Facultad actualizada exitosamente.');
    }

    public function destroy(Faculty $faculty)
    {
        $faculty->delete();

        return redirect()->route('faculties.index')
            ->with('success', 'Facultad eliminada exitosamente.');
    }
} 
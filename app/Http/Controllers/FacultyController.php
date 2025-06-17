<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::all();
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
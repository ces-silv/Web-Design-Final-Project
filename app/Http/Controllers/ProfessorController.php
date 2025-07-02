<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfessorController extends Controller
{
    /*public function __construct(){
        // Solamente los administradores podrán gestionar los datos de los profesores
        $this->middleware(['auth', 'role:admin']);
    }*/

    /**
     * Display a listing of the resource.
    */
    public function index(Request $request)
    {
        $query = Professor::query();

        if ($search = $request->input('search')) {
            $query->where(fn($q)=>
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            );
        }

        if ($initial = $request->input('initial')) {
            $query->where('last_name', 'like', "{$initial}%");
        }

        if ($sort = $request->input('sort')) {
            match($sort) {
                'name_asc'  => $query->orderBy('last_name', 'asc'),
                'name_desc' => $query->orderBy('last_name', 'desc'),
                'newest'    => $query->orderBy('created_at', 'desc'),
                'oldest'    => $query->orderBy('created_at', 'asc'),
                default     => null
            };
        }

        $professors = $query->paginate(10)->withQueryString();

        return view('professors.index', compact('professors'));
    }
 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('professors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:professors,email',
            'password'   => 'required|string|min:8|confirmed',
        ], [
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required'  => 'El apellido es obligatorio.',
            'email.required'      => 'El correo electrónico es obligatorio.',
            'email.email'         => 'El correo electrónico debe ser válido.',
            'email.unique'        => 'El correo electrónico ya está en uso.',
            'password.required'   => 'La contraseña es obligatoria.',
            'password.min'        => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'  => 'Las contraseñas no coinciden.',
        ]);

        // Creación del profesor en la tabla de usuarios
        DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => "{$data['first_name']} {$data['last_name']}",
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
                'role'     => User::ROLE_PROFESSOR,
                'status'   => User::STATUS_ACTIVE,
            ]);

            // Creación del profesor en la tabla de profesores
            Professor::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => bcrypt($data['password']),
            ]);
        });

        return redirect()->route('professors.index')
            ->with('alert', [
                'type'    => 'success', 
                'message' => 'Profesor creado exitosamente.'
            ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Professor $professor)
    {
        return view('professors.show', compact('professor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Professor $professor)
    {
        return view('professors.edit', compact('professor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Professor $professor)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:professors,email,' . $professor->id,
            'password'   => $request->filled('password') ? 'string|min:8|confirmed' : 'nullable',
        ], [
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required'  => 'El apellido es obligatorio.',
            'email.required'      => 'El correo electrónico es obligatorio.',
            'email.email'         => 'El correo electrónico debe ser válido.',
            'email.unique'        => 'El correo electrónico ya está en uso.',
            'password.min'        => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'  => 'Las contraseñas no coinciden.',
        ]);

        // Se "hasheará" la contraseña al proporcionarse una nueva
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->input('password'));
        } else {
            unset($data['password']);
        }

        $professor->update($data);
        return redirect()->route('professors.index')
            ->with('alert', [
                'type'    => 'success',
                'message' => 'Profesor actualizado exitosamente.'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Professor $professor)
    {
        // En caso que el profesor tenga cursos relacionados, estos serán eliminados
        /* if ($professor->courses()->exists()) {
            $professor->courses()->delete();
        } */

        $professor->delete();
        return redirect()->route('professors.index')
            ->with('alert', [
                'type'    => 'success',
                'message' => 'Profesor eliminado exitosamente.'
            ]);
    }
}

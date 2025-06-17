
{{-- filepath: resources/views/justifications/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Justificaciones</h1>
    <a href="{{ route('justifications.create') }}" class="btn btn-primary mb-3">Nueva Justificación</a>

    @if(session('alert'))
        <div class="alert alert-{{ session('alert.type') }}">{{ session('alert.message') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Motivo</th>
                <th>Archivo</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($justifications as $justification)
            <tr>
                <td>{{ $justification->user->name ?? 'N/A' }}</td>
                <td>{{ Str::limit($justification->reason, 40) }}</td>
                <td>
                    @if($justification->attachment)
                        <a href="{{ asset('storage/' . $justification->attachment) }}" target="_blank">Ver archivo</a>
                    @else
                        -
                    @endif
                </td>
                <td>{{ ucfirst($justification->status) }}</td>
                <td>{{ $justification->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('justifications.show', $justification) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('justifications.edit', $justification) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('justifications.destroy', $justification) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $justifications->links() }}
</div>
@endsection
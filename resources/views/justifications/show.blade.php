{{-- filepath: resources/views/justifications/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle de Justificación</h1>
    <div class="mb-3">
        <strong>Usuario:</strong> {{ $justification->user->name ?? 'N/A' }}
    </div>
    <div class="mb-3">
        <strong>Motivo:</strong> {{ $justification->reason }}
    </div>
    <div class="mb-3">
        <strong>Archivo:</strong>
        @if($justification->attachment)
            <a href="{{ asset('storage/' . $justification->attachment) }}" target="_blank">Ver archivo</a>
        @else
            No adjunto
        @endif
    </div>
    <div class="mb-3">
        <strong>Estado:</strong> {{ ucfirst($justification->status) }}
    </div>
    <div class="mb-3">
        <strong>Fecha:</strong> {{ $justification->created_at->format('d/m/Y H:i') }}
    </div>
    <a href="{{ route('justifications.index') }}" class="btn btn-secondary">Volver</a>
    <a href="{{ route('justifications.edit', $justification) }}" class="btn btn-warning">Editar</a>
</div>
@endsection
<<<<<<< HEAD
{{-- filepath: resources/views/justifications/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Justificación</h1>
    <form action="{{ route('justifications.update', $justification) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label for="reason" class="form-label">Motivo</label>
            <textarea name="reason" id="reason" class="form-control" required>{{ old('reason', $justification->reason) }}</textarea>
            @error('reason') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending" {{ $justification->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="approved" {{ $justification->status == 'approved' ? 'selected' : '' }}>Aprobada</option>
                <option value="rejected" {{ $justification->status == 'rejected' ? 'selected' : '' }}>Rechazada</option>
            </select>
            @error('status') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="attachment" class="form-label">Archivo (opcional, sube para reemplazar)</label>
            <input type="file" name="attachment" id="attachment" class="form-control" accept="image/*,.pdf">
            @if($justification->attachment)
                <p>Archivo actual: <a href="{{ asset('storage/' . $justification->attachment) }}" target="_blank">Ver archivo</a></p>
            @endif
            @error('attachment') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('justifications.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
=======
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-file-circle-check text-2xl text-[#31c0d3] dark:text-[#31c0d3] mr-2"></i>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                {{ __('Editar Justificación') }}
            </h2>
        </div>
    </x-slot>
    <div class="min-h-screen py-12 bg-white/10 dark:bg-gray-900 transition-colors">
        <div class="max-w-lg mx-auto bg-white/80 dark:bg-gray-800/80 border border-[#0b545b]/20 dark:border-gray-700 shadow-lg p-8 rounded-2xl">
            @include('justifications._form', ['justification' => $justification])
        </div>
    </div>
</x-app-layout>
>>>>>>> parent of 3a1f45f (Merge branch 'main' of https://github.com/ces-silv/Web-Design-Final-Project into feature/justifications)

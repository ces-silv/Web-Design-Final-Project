<<<<<<< HEAD

{{-- filepath: resources/views/justifications/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nueva Justificación</h1>
    <form action="{{ route('justifications.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="reason" class="form-label">Motivo</label>
            <textarea name="reason" id="reason" class="form-control" required>{{ old('reason') }}</textarea>
            @error('reason') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="attachment" class="form-label">Archivo (opcional)</label>
            <input type="file" name="attachment" id="attachment" class="form-control" accept="image/*,.pdf">
            @error('attachment') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-success">Enviar</button>
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
                {{ __('Crear Justificación') }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen py-12 bg-white/10 dark:bg-gray-900 transition-colors">
        <div class="max-w-lg mx-auto bg-white/80 dark:bg-gray-800/80 border border-[#0b545b]/20 dark:border-gray-700 shadow-lg p-8 rounded-2xl">
            @include('justifications._form')
        </div>
    </div>
</x-app-layout>
>>>>>>> parent of 3a1f45f (Merge branch 'main' of https://github.com/ces-silv/Web-Design-Final-Project into feature/justifications)

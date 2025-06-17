
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
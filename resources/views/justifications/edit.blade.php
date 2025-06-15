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
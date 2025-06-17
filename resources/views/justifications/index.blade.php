<<<<<<< HEAD

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
=======
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#31c0d3]/20 dark:bg-[#0b545b]/30">
                    <i class="fa-solid fa-file-circle-check text-xl text-[#31c0d3] dark:text-[#31c0d3]"></i>
                </span>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                    {{ __('Justificaciones') }}
                </h2>
            </div>
            <a href="{{ route('justifications.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#31c0d3] dark:focus:ring-offset-gray-900 transition">
                <i class="fas fa-plus"></i>
                {{ __('Nueva Justificación') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12 bg-gradient-to-r from-[#31c0d3]/10 to-[#0b545b]/10 dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <form method="GET" action="{{ route('justifications.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between mb-6 gap-4">
                <div class="relative flex-1 max-w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#31c0d3] dark:text-[#31c0d3]">
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="{{ __('Buscar justificaciones') }}"
                        class="w-full pl-10 pr-16 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focus:border-[#31c0d3] transition"
                    />
                    <button
                        type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-[#31c0d3] hover:bg-[#0b545b] text-white rounded-lg transition flex items-center gap-1 shadow"
                        title="{{ __('Buscar') }}"
                    >
                        <i class="fas fa-arrow-right"></i>
                        <span class="sr-only">{{ __('Buscar') }}</span>
                    </button>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto bg-white/80 dark:bg-gray-800/800 shadow-xl rounded-xl">
                <table class="min-w-full divide-y divide-[#0b545b]/20 dark:divide-gray-700">
                    <thead class="bg-[#31c0d3] dark:bg-[#0b545b]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                {{ __('Clase') }}
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                {{ __('Periodo') }}
                            </th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-white">
                                {{ __('Acciones') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-[#0b545b]/20 dark:divide-gray-700">
                        @forelse ($justifications as $justification)
                            <tr>
                                <td class="px-6 py-4 text-[#231f20] dark:text-gray-300">
                                    {{ $justification->class->name }}
                                    <div class="text-sm text-[#0b545b] dark:text-[#31c0d3]">
                                        {{ $justification->class->faculty->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#231f20] dark:text-gray-300">
                                    {{ $justification->start_date->format('d/m/Y') }} - 
                                    {{ $justification->end_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col md:flex-row items-center justify-center gap-2">
                                        <!-- View Button -->
                                        <a href="{{ route('justifications.show', $justification) }}"
                                           title="{{ __('Ver') }}"
                                           class="w-8 h-8 flex items-center justify-center bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white rounded-full transition">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('justifications.edit', $justification) }}"
                                           title="{{ __('Editar') }}"
                                           class="w-8 h-8 flex items-center justify-center bg-yellow-500 hover:bg-yellow-400 text-white rounded-full transition">
                                            <i class="fas fa-pen text-sm"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('justifications.destroy', $justification) }}"
                                              method="POST"
                                              id="delete-justification-{{ $justification->id }}"
                                              class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                    class="swal-confirm w-8 h-8 flex items-center justify-center bg-red-600 hover:bg-red-500 text-white rounded-full transition"
                                                    data-form-id="delete-justification-{{ $justification->id }}"
                                                    data-title="{{ __('Eliminar justificación') }}"
                                                    data-text="{{ __('¿Seguro que deseas eliminar esta justificación?') }}"
                                                    data-icon="warning"
                                                    data-confirm="{{ __('Sí, eliminar') }}"
                                                    data-cancel="{{ __('Cancelar') }}"
                                                    title="{{ __('Eliminar') }}">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-400">
                                    {{ __('No hay justificaciones registradas.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $justifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
>>>>>>> parent of 3a1f45f (Merge branch 'main' of https://github.com/ces-silv/Web-Design-Final-Project into feature/justifications)

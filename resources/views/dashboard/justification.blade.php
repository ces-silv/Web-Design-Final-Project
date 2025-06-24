<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-file-circle-check text-2xl text-[#31c0d3] dark:text-[#31c0d3] mr-2"></i>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                {{ __('Detalles de Justificación') }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen py-12 bg-white/10 dark:bg-gray-900 transition-colors">
        <div class="max-w-lg mx-auto bg-white/80 dark:bg-gray-800/80 border border-[#0b545b]/20 dark:border-gray-700 shadow-lg p-8 rounded-2xl">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[#231f20] dark:text-gray-300 mb-1">
                        {{ __('Estudiante') }}
                    </label>
                    <p class="text-[#231f20] dark:text-gray-100 bg-white/80 dark:bg-gray-700 px-4 py-2 rounded-lg border border-[#0b545b]/20 dark:border-gray-600">
                        {{ $justification->student->name }} ({{ $justification->student->email }})
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#231f20] dark:text-gray-300 mb-1">
                        {{ __('Clase') }}
                    </label>
                    <p class="text-[#231f20] dark:text-gray-100 bg-white/80 dark:bg-gray-700 px-4 py-2 rounded-lg border border-[#0b545b]/20 dark:border-gray-600">
                        {{ $justification->class->name }} ({{ $justification->class->faculty->name }})
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#231f20] dark:text-gray-300 mb-1">
                        {{ __('Periodo') }}
                    </label>
                    <p class="text-[#231f20] dark:text-gray-100 bg-white/80 dark:bg-gray-700 px-4 py-2 rounded-lg border border-[#0b545b]/20 dark:border-gray-600">
                        {{ $justification->start_date->format('d/m/Y') }} - {{ $justification->end_date->format('d/m/Y') }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#231f20] dark:text-gray-300 mb-1">
                        {{ __('Descripción') }}
                    </label>
                    <p class="text-[#231f20] dark:text-gray-100 bg-white/80 dark:bg-gray-700 px-4 py-2 rounded-lg border border-[#0b545b]/20 dark:border-gray-600 min-h-[100px]">
                        {{ $justification->description }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#231f20] dark:text-gray-300 mb-1">
                        {{ __('Estado') }}
                    </label>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $justification->status_class }}">
                            {{ $justification->status_text }}
                        </span>
                        @if($justification->isPending())
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Esperando revisión del administrador') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#231f20] dark:text-gray-300 mb-1">
                        {{ __('Documentos') }}
                    </label>
                    @if($justification->documents->count() > 0)
                        <div class="space-y-2">
                            @foreach($justification->documents as $document)
                                <div class="flex items-center justify-between bg-white/80 dark:bg-gray-700 px-4 py-2 rounded-lg border border-[#0b545b]/20 dark:border-gray-600">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-file text-[#31c0d3] dark:text-[#31c0d3]"></i>
                                        <span class="text-[#231f20] dark:text-gray-100">{{ $document->file_name }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ number_format($document->size / 1024, 1) }} KB)</span>
                                    </div>
                                    <a href="{{ route('justifications.download', ['justification' => $justification, 'document' => $document]) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1 bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white rounded-lg transition text-sm">
                                        <i class="fas fa-download"></i>
                                        {{ __('Descargar') }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 bg-white/80 dark:bg-gray-700 px-4 py-2 rounded-lg border border-[#0b545b]/20 dark:border-gray-600">
                            {{ __('No hay documentos asociados.') }}
                        </p>
                    @endif
                </div>
                <div class="pt-4 flex justify-end gap-2">
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 text-[#231f20] dark:text-gray-300 hover:bg-[#31c0d3]/10 dark:hover:bg-gray-700 rounded-lg transition">
                        {{ __('Volver al Dashboard') }}
                    </a>
                    @if(auth()->user()->role === 'admin' && $justification->isPending())
                        <form action="{{ route('justifications.approve', $justification) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg transition"
                                    onclick="return confirm('¿Estás seguro de que deseas aprobar esta justificación?')">
                                <i class="fas fa-check mr-1"></i> {{ __('Aprobar') }}
                            </button>
                        </form>
                        <form action="{{ route('justifications.reject', $justification) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg transition"
                                    onclick="return confirm('¿Estás seguro de que deseas rechazar esta justificación?')">
                                <i class="fas fa-times mr-1"></i> {{ __('Rechazar') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
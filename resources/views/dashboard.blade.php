<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#31c0d3]/20 dark:bg-[#0b545b]/30">
                    <i class="fas fa-tachometer-alt text-xl text-[#31c0d3] dark:text-[#31c0d3]"></i>
                </span>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            @if(auth()->user()->role === 'admin')
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Panel de Administrador') }}
                </div>
            @else
                <a href="{{ route('justifications.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#31c0d3] dark:focus:ring-offset-gray-900 transition">
                    <i class="fas fa-plus"></i>
                    {{ __('Nueva Justificación') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 sm:py-12 bg-gradient-to-r from-[#31c0d3]/10 to-[#0b545b]/10 dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('alert'))
                <div class="mb-6 p-4 rounded-lg {{ session('alert')['type'] === 'success' ? 'bg-green-100 border border-green-400 text-green-700 dark:bg-green-900 dark:border-green-700 dark:text-green-300' : 'bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:border-red-700 dark:text-red-300' }}" role="alert">
                    <span class="block sm:inline">{{ session('alert')['message'] }}</span>
                </div>
            @endif

            <!-- Sección de búsqueda y filtrado -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between mb-6 gap-4">
                <!-- Búsqueda con icono y botón integrado -->
                <div class="relative flex-1 max-w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#31c0d3] dark:text-[#31c0d3]">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ auth()->user()->role === 'admin' ? 'Buscar por estudiante, clase o descripción...' : 'Buscar justificación...' }}" 
                           class="w-full pl-10 pr-16 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focus:border-[#31c0d3] transition"/>
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-[#31c0d3] hover:bg-[#0b545b] text-white rounded-lg transition flex items-center gap-1 shadow" title="{{ __('Buscar') }}">
                        <i class="fas fa-arrow-right"></i>
                        <span class="sr-only">{{ __('Buscar') }}</span>
                    </button>
                </div>

                <!-- Filtro de estado -->
                <div class="flex items-center gap-2">
                    <label for="status" class="sr-only">{{ __('Filtrar por estado') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#31c0d3] dark:text-[#31c0d3]">
                            <i class="fas fa-filter"></i>
                        </span>
                        <select name="status" id="status" onchange="this.form.submit()" class="pl-10 pr-8 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focus:border-[#31c0d3] transition appearance-none">
                            <option value="">{{ __('Todos los estados') }}</option>
                            <option value="En Proceso" @selected(request('status')==='En Proceso')>{{ __('En Proceso') }}</option>
                            <option value="Aceptada" @selected(request('status')==='Aceptada')>{{ __('Aceptada') }}</option>
                            <option value="Rechazada" @selected(request('status')==='Rechazada')>{{ __('Rechazada') }}</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                </div>
            </form>

            <!-- Tabla de justificaciones -->
            <div class="overflow-x-auto bg-white/80 dark:bg-gray-900/90 shadow-xl rounded-xl border border-[#0b545b]/10 dark:border-gray-700">
                <table class="min-w-full divide-y divide-[#0b545b]/10 dark:divide-gray-700">
                    <thead class="bg-[#31c0d3]/90 dark:bg-[#0b545b]/90">
                        <tr>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                    {{ __('Estudiante') }}
                                </th>
                            @endif
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                {{ __('Clase') }}
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                {{ __('Período') }}
                            </th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-white">
                                {{ __('Estado') }}
                            </th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-white">
                                {{ __('Documentos') }}
                            </th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-white">
                                {{ __('Acciones') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-[#0b545b]/10 dark:divide-gray-700">
                        @forelse($justifications as $justification)
                            <tr class="transition hover:bg-[#31c0d3]/10 dark:hover:bg-[#0b545b]/20 even:bg-[#31c0d3]/5 dark:even:bg-[#0b545b]/10">
                                @if(auth()->user()->role === 'admin')
                                    <td class="px-6 py-4 text-[#231f20] dark:text-gray-200">
                                        <div>
                                            <div class="font-medium">{{ $justification->student->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $justification->student->email }}</div>
                                        </div>
                                    </td>
                                @endif
                                <td class="px-6 py-4 text-[#231f20] dark:text-gray-200">
                                    <div>
                                        <div class="font-medium">{{ $justification->class->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $justification->class->faculty->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#231f20] dark:text-gray-200">
                                    <div class="text-sm">
                                        <div>{{ $justification->start_date->format('d/m/Y') }}</div>
                                        <div class="text-gray-500 dark:text-gray-400">{{ __('hasta') }}</div>
                                        <div>{{ $justification->end_date->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="{{ $justification->status_class }} inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        {{ $justification->status_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                        {{ $justification->documents->count() }} {{ __('doc.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col md:flex-row items-center justify-center gap-2">
                                        <!-- Botón Ver -->
                                        <a href="{{ route('dashboard.justifications.show', $justification) }}" 
                                           title="{{ __('Ver detalles') }}" 
                                           class="w-8 h-8 flex items-center justify-center bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white rounded-full transition shadow focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'admin' ? '6' : '5' }}" class="px-6 py-4 text-center text-gray-400 dark:text-gray-500">
                                    {{ __('No hay justificaciones para mostrar.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-6 flex justify-center">
                {{ $justifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>



@php
  use Carbon\Carbon;
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#31c0d3]/20 dark:bg-[#0b545b]/30">
                    <i class="fa-solid fa-book text-xl text-[#31c0d3] dark:text-[#31c0d3]"></i>
                </span>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                    {{ __('Clases') }}
                </h2>
            </div>
            <a href="{{ route('classes.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#31c0d3] dark:focus:ring-offset-gray-900 transition">
                <i class="fas fa-plus"></i>
                {{ __('Crear Clase') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12 bg-gradient-to-r from-[#31c0d3]/10 to-[#0b545b]/10 dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Sección de búsqueda y filtrado -->
        <form method="GET" action="{{ route('classes.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between mb-6 gap-4">
            <!-- Búsqueda con icono y botón integrado -->
            <div class="relative flex-1 max-w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#31c0d3] dark:text-[#31c0d3]">
                    <i class="fas fa-search"></i>
                </span>
                <!-- name en lugar de search -->
                <input
                    type="text"
                    name="name"
                    value="{{ request('name') }}"
                    placeholder="{{ __('Buscar asignatura por nombre') }}"
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

            <!-- Filtro mejorado con icono -->
            <div class="flex items-center gap-2">
                <label for="sort" class="sr-only">{{ __('Filtrar') }}</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#31c0d3] dark:text-[#31c0d3]">
                        <i class="fas fa-filter"></i>
                    </span>

                    <!-- oculto para enviar sort_dir -->
                    <input type="hidden" name="sort_dir" value="{{ request('sort_dir', 'asc') }}">

                    <!-- ahora envía sort_by; JS se encarga de ajustar sort_dir antes de submit -->
                    <select
                        name="sort_by"
                        id="sort"
                        onchange="document.querySelector('input[name=sort_dir]').value = this.selectedIndex === 1 ? 'desc' : 'asc'; this.form.submit();"
                        class="pl-10 pr-8 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focus:border-[#31c0d3] transition appearance-none"
                        title="{{ __('Filtrar lista de clases') }}"
                    >
                        <option value="name" @selected(request('sort_by')==='name' && request('sort_dir')==='asc')>
                            {{ __('Nombre A-Z') }}
                        </option>
                        <option value="name" @selected(request('sort_by')==='name' && request('sort_dir')==='desc')>
                            {{ __('Nombre Z-A') }}
                        </option>
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </div>
            </div>
        </form>
            <!-- Tabla responsiva -->
            <div class="overflow-x-auto bg-white/80 dark:bg-gray-800/800 shadow-xl rounded-xl">
                <table class="min-w-full divide-y divide-[#0b545b]/20 dark:divide-gray-700">
                    <thead class="bg-[#31c0d3] dark:bg-[#0b545b]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                    {{__("Nombre")}}
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                    {{__("Cantidad de Grupos")}}
                            </th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-white">
                    {{__("Acciones")}}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-[#0b545b]/20 dark:divide-gray-700">
                        @forelse ($classes as $class)
                            <tr>
                                <td class="px-6 py-4 text-[#231f20] dark:text-gray-300">
                                    {{ $class->name }}
                                </td>
                                <td class="px-6 py-4 text-[#231f20] dark:text-gray-300">
                                    {{ count($class->groups)}}
                                </td>
<td class="px-6 py-4 text-center">
    <div x-data="{ showModal{{ $class->id }}: false }"
         class="flex flex-col md:flex-row items-center justify-center gap-2">
        <!-- Botón Ver -->
        <button @click="showModal{{ $class->id }} = true"
                title="{{ __('Ver detalles') }}"
                class="w-8 h-8 flex items-center justify-center bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white rounded-full transition">
            <i class="fas fa-eye text-sm"></i>
        </button>

        <!-- Botón Editar -->
        <a href="{{ route('classes.edit', $class) }}"
           title="{{ __('Editar') }}"
           class="w-8 h-8 flex items-center justify-center bg-yellow-500 hover:bg-yellow-400 text-white rounded-full transition">
            <i class="fas fa-pen text-sm"></i>
        </a>

        <!-- Botón Eliminar -->
        <form action="{{ route('classes.destroy', $class) }}"
              method="POST"
              id="delete-class-{{ $class->id }}"
              class="inline-block">
            @csrf @method('DELETE')
            <button type="button"
                    class="swal-confirm w-8 h-8 flex items-center justify-center bg-red-600 hover:bg-red-500 text-white rounded-full transition"
                    data-form-id="delete-class-{{ $class->id }}"
                    data-title="{{ __('Eliminar clase') }}"
                    data-text="{{ __('¿Seguro que deseas eliminar la clase') }} {{ $class->name }}?"
                    data-icon="warning"
                    data-confirm="{{ __('Sí, eliminar') }}"
                    data-cancel="{{ __('Cancelar') }}"
                    title="{{ __('Eliminar') }}">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </form>

        <!-- Modal de vista -->
        <div x-show="showModal{{ $class->id }}"
             x-cloak
             @click.outside="showModal{{ $class->id }} = false"
             x-transition.opacity
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div @click.outside="showModal{{ $class->id }} = false"
                 class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white w-full max-w-3xl mx-4 p-6 rounded shadow-lg overflow-y-auto max-h-[90vh] transition-colors">
                {{-- Header --}}
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">{{ __('Detalles de la clase') }}</h2>
                    <button @click="showModal{{ $class->id }} = false"
                            class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition text-xl">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Cuerpo del modal -->
                <div class="space-y-4 text-left">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">{{ __('Nombre') }}</label>
                        <p class="text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-800 px-3 py-2 rounded">
                            {{ $class->name }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">{{ __('Facultad') }}</label>
                        <p class="text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-800 px-3 py-2 rounded">
                            {{ $class->faculty->name }}
                        </p>
                    </div>

                    @foreach($class->groups as $group)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <h3 class="font-medium">{{ __('Grupo :num', ['num' => $loop->iteration]) }}</h3>
                            <p class="text-sm">
                                {{ __('Profesor:') }}
                                {{ $group->professor->first_name }} {{ $group->professor->last_name }}
                            </p>
                            <ul class="text-sm list-disc list-inside">
                                @foreach($group->days as $day)
                                    @php
                                         $start = Carbon::parse($day->start_time);
                                         $end   = $start->copy()->addMinutes($day->duration_in_min);
                                     @endphp

                                    <li>
                                        {{ __('Día :weekday, inicia a las :start, y termina :end', [
                                            'weekday'  => ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'][$day->weekday],
                                             'start'   => $start->format('g:i A'),
                                                'end'     => $end->format('g:i A'),
                                        ]) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>

                <!-- Footer -->
                <div class="mt-6 text-right">
                    <button @click="showModal{{ $class->id }} = false"
                            class="bg-gray-200 hover:bg-[#31c0d3] text-gray-900 hover:text-white dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white px-4 py-2 rounded transition">
                        {{ __('Cerrar') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-400">
                                    {{ __('No hay clases registradas.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            {{-- Paginación --}}
            <div class="mt-6">
                {{ $classes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

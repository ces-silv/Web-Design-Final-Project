<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#31c0d3]/20 dark:bg-[#0b545b]/30">
                    <i class="fa-solid fa-building-columns text-xl text-[#31c0d3] dark:text-[#31c0d3]"></i>
                </span>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                    {{ __('Facultades') }}
                </h2>
            </div>
            <a href="{{ route('faculties.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#31c0d3] dark:focus:ring-offset-gray-900 transition">
                <i class="fas fa-plus"></i>
                {{ __('Crear Facultad') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12 bg-gradient-to-r from-[#31c0d3]/10 to-[#0b545b]/10 dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Sección de búsqueda y filtrado -->
            <form method="GET" action="{{ route('faculties.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between mb-6 gap-4">
                <!-- Búsqueda con icono y botón integrado -->
                <div class="relative flex-1 max-w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#31c0d3] dark:text-[#31c0d3]">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Buscar facultad por nombre...') }}" class="w-full pl-10 pr-16 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focus:border-[#31c0d3] transition"/>
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-[#31c0d3] hover:bg-[#0b545b] text-white rounded-lg transition flex items-center gap-1 shadow" title="{{ __('Buscar') }}">
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
                        <select name="sort" id="sort" onchange="this.form.submit()" class="pl-10 pr-8 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focus:border-[#31c0d3] transition appearance-none" title="{{ __('Filtrar lista de facultades') }}">
                            <option value="">{{ __('Filtrar') }}</option>
                            <option value="name_asc"  @selected(request('sort')==='name_asc')>{{ __('Nombre A-Z') }}</option>
                            <option value="name_desc" @selected(request('sort')==='name_desc')>{{ __('Nombre Z-A') }}</option>
                            <option value="newest"    @selected(request('sort')==='newest')>{{ __('Más recientes') }}</option>
                            <option value="oldest"    @selected(request('sort')==='oldest')>{{ __('Más antiguas') }}</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto bg-white/80 dark:bg-gray-800/800 shadow-xl rounded-xl">
                <table class="min-w-full divide-y divide-[#0b545b]/20 dark:divide-gray-700">
                    <thead class="bg-[#31c0d3] dark:bg-[#0b545b]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white">
                                {{ __('Nombre') }}
                            </th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-white">
                                {{ __('Acciones') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-[#0b545b]/20 dark:divide-gray-700">
                        @forelse($faculties as $faculty)
                            <tr class="even:bg-[#31c0d3]/10 dark:even:bg-gray-800 transition">
                                <td class="px-6 py-4 text-[#231f20] dark:text-gray-300">
                                    {{ $faculty->name }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col md:flex-row items-center justify-center gap-2">
                                        <!-- Botón Editar -->
                                        <a href="{{ route('faculties.edit', $faculty) }}" title="{{ __('Editar') }}" class="w-8 h-8 flex items-center justify-center bg-yellow-500 hover:bg-yellow-400 text-white rounded-full transition">
                                            <i class="fas fa-pen text-sm"></i>
                                        </a>

                                        <!-- Botón Eliminar -->
                                        <form action="{{ route('faculties.destroy', $faculty) }}" method="POST" id="delete-faculty-{{ $faculty->id }}" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="button" class="swal-confirm w-8 h-8 flex items-center justify-center bg-red-600 hover:bg-red-500 text-white rounded-full transition" data-form-id="delete-faculty-{{ $faculty->id }}" data-title="{{ __('Eliminar facultad') }}" data-text="{{ __('¿Seguro que deseas eliminar la facultad') }} {{ $faculty->name }}?" data-icon="warning" data-confirm="{{ __('Sí, eliminar') }}" data-cancel="{{ __('Cancelar') }}" title="{{ __('Eliminar') }}">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-gray-400">
                                    {{ __('No hay facultades registradas.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-6">
                {{ $faculties->links() }}
            </div>
        </div>
    </div>
</x-app-layout> 
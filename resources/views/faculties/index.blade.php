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

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

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
        </div>
    </div>
</x-app-layout> 
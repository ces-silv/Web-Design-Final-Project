<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-building-columns text-2xl text-[#31c0d3] dark:text-[#31c0d3] mr-2"></i>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                {{ __('Editar Facultad') }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen py-12 bg-white/10 dark:bg-gray-900 transition-colors">
        <div class="max-w-lg mx-auto bg-white/80 dark:bg-gray-800/80 border border-[#0b545b]/20 dark:border-gray-700 shadow-lg p-8 rounded-2xl">
            <form action="{{ route('faculties.update', $faculty) }}" method="POST" class="space-y-6" novalidate autocomplete="off">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
                        {{ __('Nombre de la Facultad') }}
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $faculty->name) }}" placeholder="e.g.: Facultad de Ciencias" class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 placeholder-[#0b545b]/50 dark:placeholder-[#ffffff]/20 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focurs:border-[#31c0d3] transition"/>
                    <div class="@error('name') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>@error('name'){{ $message }}@enderror</span>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('faculties.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" class="bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white font-semibold py-2 px-4 rounded-lg transition">
                        {{ __('Actualizar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 
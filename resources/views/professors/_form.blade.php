<!--
    Este formulario es utilizado para ambas acciones de creación y edición de un profesor.
    La variable $isEdit se usa aquí para determinar si estamos en el modo de edición o creación, respectivamente.
    Si $isEdit es verdadero, se mostrará el formulario de edición, de lo contrario, se mostrará el formulario de creación.
-->

@php
    // Aquí se detecta si estamos en edición (se pasó $professor) o creación
    $isEdit = isset($professor) && $professor !== null;
@endphp

<form action="{{ $isEdit ? route('professors.update', $professor) : route('professors.store') }}" method="POST" class="space-y-6" novalidate autocomplete="off">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif
    
    <!-- Campo de nombres del profesor -->
    <div>
        <label for="first_name" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
            {{ __('Nombres') }}
        </label>
        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $professor->first_name ?? '') }}" placeholder="e.g.: Juan" class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 placeholder-[#0b545b]/50 dark:placeholder-[#ffffff]/20 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focurs:border-[#31c0d3] transition"/>
        <div class=" @error('first_name') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('first_name'){{ $message }}@enderror</span>
        </div>
    </div>

    <!-- Campo de apellidos del profesor -->
    <div>
        <label for="last_name" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
            {{ __('Apellidos') }}
        </label>
        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $professor->last_name ?? '') }}" placeholder="e.g.: Pérez" class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 placeholder-[#0b545b]/50 dark:placeholder-[#ffffff]/20 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focurs:border-[#31c0d3] transition"/>
        <div class=" @error('last_name') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('last_name'){{ $message }}@enderror</span>
        </div>
    </div>

    <!-- Campo de correo electrónico del profesor -->
    <div>
        <label for="email" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
            {{ __('Correo electrónico') }}
        </label>
        <input type="text" name="email" id="email" value="{{ old('email', $professor->email ?? '') }}" placeholder="e.g.: usuario@uamv.edu.ni" class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 placeholder-[#0b545b]/50 dark:placeholder-[#ffffff]/20 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focurs:border-[#31c0d3] transition"/>
        <div class=" @error('email') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded"> 
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('email'){{ $message }}@enderror</span>
        </div>
    </div>

    <!-- Campo de contraseña del profesor (requerida sólo en creación) -->
    <div>
        <label for="password" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
            {{ $isEdit ? __('Nueva contraseña (opcional)') : __('Contraseña') }}
        </label>
        <input type="password" name="password" id="password" value="{{ old('password') }}" placeholder="{{ $isEdit ? __('Déjala en blanco para no cambiarla') : ''}}" class="block w-full px-4 py-2 bg-white/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 placeholder-[#0b545b]/50 dark:placeholder-[#ffffff]/20 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focurs:border-[#31c0d3] transition"/>
        <div class=" @error('password') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1.5 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded"> 
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('password'){{ $message }}@enderror</span>
        </div>
    </div>

    <!-- Campo de confirmación de contraseña -->
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-[#231f20] dark:text-gray-200 mb-1">
            {{ __('Confirmar contraseña') }}
        </label>
        <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="{{ $isEdit ? __('—') : '' }}" class="block w-full px-4 py-2 bg-[#ffffff]/80 dark:bg-gray-700 border border-[#0b545b]/20 dark:border-gray-600 rounded-lg text-[#231f20] dark:text-gray-100 placeholder-[#0b545b]/50 dark:placeholder-[#ffffff]/20 focus:outline-none focus:ring-2 focus:ring-[#31c0d3] focus:border-[#31c0d3] transition"/>
        <div class=" @error('password') flex @else hidden @enderror items-center gap-1.5 text-red-600 dark:text-red-400 text-xs mt-1.5 bg-red-50 dark:bg-[#3C0000] p-1.5 border border-red-300 dark:border-red-700 rounded"> 
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>@error('password'){{ $message }}@enderror</span>
        </div>        
    </div>

    <!-- Botones de acción -->
    <div class="flex flex-col gap-3">
        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 bg-[#31c0d3] hover:bg-[#0b545b] dark:bg-[#0b545b] dark:hover:bg-[#31c0d3] text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-[#31c0d3] dark:focus:ring-offset-gray-900 transition-all">
            <i class="fas {{ $isEdit ? 'fa-user-edit' : 'fa-user-plus' }}"></i>
            {{ $isEdit ? __('Actualizar Profesor') : __('Crear Profesor') }}
        </button>
        <a href="{{ route('professors.index') }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 text-[#231f20] dark:text-gray-300 hover:bg-[#31c0d3]/10 dark:hover:bg-gray-700 rounded-lg transition">
            <i class="fas fa-arrow-left"></i>
            {{ __('Cancelar') }}
        </a>
    </div>
</form>
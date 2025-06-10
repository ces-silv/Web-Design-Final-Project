<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro | Justificaciones UAM</title>
    <!-- Fuentes -->
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
      rel="stylesheet"
    />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      body {
        font-family: 'Roboto', sans-serif;
      }
    </style>
  </head>
  <body class="min-h-screen bg-gradient-to-r from-[#008D99] to-[#b2e6eb] flex items-center justify-center">
    <div class="container mx-auto px-4">
      <div class="flex flex-col md:flex-row items-center">
        <!-- Sección izquierda: Formulario de registro -->
        <div class="md:w-1/2 py-12 pr-8 text-white">
          <h1 class="text-5xl font-bold mb-6">
            Crea tu cuenta
          </h1>
          <p class="text-xl mb-8 leading-relaxed">
            Regístrate para gestionar tus justificaciones de manera rápida, segura y sencilla.
          </p>
          <form method="POST" action="{{ route('register') }}" class="bg-white/90 rounded-xl shadow-lg p-8">
            @csrf

            <!-- Name -->
            <div class="mb-4">
              <label for="name" class="block font-semibold mb-1 text-[#008D99]">Nombre</label>
              <input id="name" class="block w-full rounded-md px-4 py-2 border border-[#b2e6eb] text-gray-900 focus:ring-2 focus:ring-[#008D99] focus:border-[#008D99] transition" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Tu nombre completo" />
              <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Email Address -->
            <div class="mb-4">
              <label for="email" class="block font-semibold mb-1 text-[#008D99]">Correo electrónico</label>
              <input id="email" class="block w-full rounded-md px-4 py-2 border border-[#b2e6eb] text-gray-900 focus:ring-2 focus:ring-[#008D99] focus:border-[#008D99] transition" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="ejemplo@correo.com" />
              <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Password -->
            <div class="mb-4">
              <label for="password" class="block font-semibold mb-1 text-[#008D99]">Contraseña</label>
              <input id="password" class="block w-full rounded-md px-4 py-2 border border-[#b2e6eb] text-gray-900 focus:ring-2 focus:ring-[#008D99] focus:border-[#008D99] transition" type="password" name="password" required autocomplete="new-password" placeholder="********" />
              <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
              <label for="password_confirmation" class="block font-semibold mb-1 text-[#008D99]">Confirmar contraseña</label>
              <input id="password_confirmation" class="block w-full rounded-md px-4 py-2 border border-[#b2e6eb] text-gray-900 focus:ring-2 focus:ring-[#008D99] focus:border-[#008D99] transition" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="********" />
              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
              <a class="underline text-sm text-[#008D99] hover:text-[#b2e6eb] transition" href="{{ route('login') }}">
                ¿Ya tienes cuenta? Inicia sesión
              </a>
              <button
                type="submit"
                class="px-8 py-3 bg-white text-black font-semibold rounded-md shadow transition-all duration-200 hover:shadow-[0_4px_24px_0_rgba(0,0,0,0.20)] hover:bg-white"
              >
                Registrarse
              </button>
            </div>
          </form>
        </div>
        <!-- Sección derecha: Imagen o ilustración -->
        <div class="md:w-1/2 py-12 pl-8 flex justify-center">
          <img
            src="{{ asset('images/img-1.png') }}"
            alt="Ilustración Justificaciones UAM"
            class="w-56 h-56 object-contain rounded-xl shadow-lg"
          />
        </div>
      </div>
    </div>
  </body>
</html>

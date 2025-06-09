<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bienvenido | Justificaciones UAM</title>
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
        <!-- Sección izquierda: Mensaje y botones -->
        <div class="md:w-1/2 py-12 pr-8 text-white">
          <h1 class="text-5xl font-bold mb-6">
            Bienvenido a <br />Justificaciones UAM
          </h1>
          <p class="text-xl mb-8 leading-relaxed">
            Agiliza el proceso para justificar tus inasistencias de forma rápida,
            segura y sencilla. Nuestra plataforma intuitiva te ayuda a gestionar
            tus justificaciones en un solo lugar.
          </p>
          <div class="flex flex-col sm:flex-row gap-4">
            @if (Route::has('login'))
              @auth
                <a
                  href="{{ url('/dashboard') }}"
                  class="px-8 py-3 bg-white text-[#008D99] font-semibold rounded-md shadow hover:bg-gray-300 transition"
                >
                  Ir al Panel
                </a>
              @else
                <a
                  href="{{ route('login') }}"
                  class="px-8 py-3 border border-white text-white font-semibold rounded-md shadow hover:bg-white hover:text-[#008D99] transition"
                >
                  Inicia sesión
                </a>
                @if (Route::has('register'))
                  <a
                    href="{{ route('register') }}"
                    class="px-8 py-3 border border-white text-white font-semibold rounded-md shadow hover:bg-white hover:text-[#008D99] transition"
                  >
                    Regístrate
                  </a>
                @endif
              @endauth
            @endif
          </div>
        </div>
        <!-- Sección derecha: Imagen o ilustración -->
        <div class="md:w-1/2 py-12 pl-8 flex justify-center">
          <img
            src="{{ asset('images/logo-1.png') }}"
            alt="Logo UAM"
            class="w-56 h-56 object-contain rounded-xl shadow-lg"
          />
        </div>
      </div>
    </div>
  </body>
</html>
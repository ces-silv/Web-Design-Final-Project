<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bienvenido | Justificaciones UAM</title>
    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"
    />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      body {
        font-family: 'Roboto', sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }
      main {
        flex: 1;
      }
      footer {
        margin-top: auto;
      }
    </style>
  </head>

  <body class="bg-gradient-to-r from-[#008D99] to-[#b2e6eb]">
    <main class="flex items-center justify-center min-h-screen">
      <div class="container mx-auto px-4">
        <div class="flex flex-col items-center text-center">
          <!-- Logo -->
          <div class="mb-8">
            <img src="{{ asset('images/logo-1_nobg.png') }}" alt="Logo UAM" class="w-64 h-64 object-contain rounded-xl shadow-lg bg-white"/>
          </div>
          
          <!-- Contenido principal -->
          <div class="max-w-2xl text-white mb-8">
            <h1 class="text-5xl font-bold mb-6">
              Bienvenido a <br />Justificaciones UAM
            </h1>
            <p class="text-xl mb-8 leading-relaxed">
              Agiliza el proceso para justificar tus inasistencias de forma rápida,
              segura y sencilla. Nuestra plataforma intuitiva te ayuda a gestionar
              tus justificaciones en un solo lugar.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
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
        </div>
      </div>
    </main>

    <footer class="text-white py-12">
      <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
        <!-- Mapa -->
        <div class="w-full">
          <iframe
            src="https://maps.google.com/maps?q=Universidad%20Americana%20UAM%20Managua&t=&z=15&ie=UTF8&iwloc=&output=embed"
            allowfullscreen
            loading="lazy"
            class="w-full h-64 rounded-lg border-0">
          </iframe>
        </div>

        <!-- Ubicación y Horario -->
        <div>
          <h3 class="text-xl font-bold mb-4">Ubicación</h3>
          <div class="flex items-start gap-3 mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-400 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
            </svg>
            <p class="text-sm leading-relaxed">
              Costado Noroeste del Camino de Oriente, Managua, Nicaragua
            </p>
          </div>

          <h3 class="text-xl font-bold mb-4">Horario de Oficinas</h3>
          <div class="flex flex-col gap-4 text-sm">
            <div class="flex items-start gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
              </svg>
              <span>Lunes a Viernes — 8:00 am – 12:00 pm | 1:30 pm – 5:30 pm</span>
            </div>
            <div class="flex items-start gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
              </svg>
              <span>Sábados — 8:00 am – 12:00 pm</span>
            </div>
          </div>
        </div>

        <!-- Contacto -->
        <div>
          <h3 class="text-xl font-bold mb-4">Contáctanos</h3>
          <div class="flex flex-col gap-3 text-sm">
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.68l1.2 3a1 1 0 01-.27 1.07l-2.2 2.2a11.042 11.042 0 005 5l2.2-2.2a1 1 0 011.07-.27l3 1.2a1 1 0 01.68.94V19a2 2 0 01-2 2h-1C8.82 21 3 15.18 3 8V7a2 2 0 010-2z"/>
              </svg>
              <a href="tel:+50522783800" class="hover:text-green-400 transition">(505) 2278-3800 ext. 5424</a>
            </div>
          </div>
        </div>

        <!-- Redes Sociales -->
        <div>
          <h3 class="text-xl font-bold mb-4">Redes Sociales</h3>
          <div class="flex flex-col gap-3 text-sm">
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.99 3.66 9.12 8.44 9.88v-6.99h-2.54v-2.89h2.54V9.41c0-2.51 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.25c-1.23 0-1.61.76-1.61 1.54v1.85h2.74l-.44 2.89h-2.3v6.99C18.34 21.12 22 16.99 22 12z"/>
              </svg>
              <a href="https://www.facebook.com/UniversidadAmericana.UAM" target="_blank" class="hover:text-green-400 transition">Facebook</a>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.49614 7.13176C9.18664 6.9549 8.80639 6.95617 8.49807 7.13509C8.18976 7.31401 8 7.64353 8 8V16C8 16.3565 8.18976 16.686 8.49807 16.8649C8.80639 17.0438 9.18664 17.0451 9.49614 16.8682L16.4961 12.8682C16.8077 12.6902 17 12.3589 17 12C17 11.6411 16.8077 11.3098 16.4961 11.1318L9.49614 7.13176ZM13.9844 12L10 14.2768V9.72318L13.9844 12Z" fill="#ffffff"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 12C0 8.25027 0 6.3754 0.954915 5.06107C1.26331 4.6366 1.6366 4.26331 2.06107 3.95491C3.3754 3 5.25027 3 9 3H15C18.7497 3 20.6246 3 21.9389 3.95491C22.3634 4.26331 22.7367 4.6366 23.0451 5.06107C24 6.3754 24 8.25027 24 12C24 15.7497 24 17.6246 23.0451 18.9389C22.7367 19.3634 22.3634 19.7367 21.9389 20.0451C20.6246 21 18.7497 21 15 21H9C5.25027 21 3.3754 21 2.06107 20.0451C1.6366 19.7367 1.26331 19.3634 0.954915 18.9389C0 17.6246 0 15.7497 0 12ZM9 5H15C16.9194 5 18.1983 5.00275 19.1673 5.10773C20.0989 5.20866 20.504 5.38448 20.7634 5.57295C21.018 5.75799 21.242 5.98196 21.4271 6.23664C21.6155 6.49605 21.7913 6.90113 21.8923 7.83269C21.9973 8.80167 22 10.0806 22 12C22 13.9194 21.9973 15.1983 21.8923 16.1673C21.7913 17.0989 21.6155 17.504 21.4271 17.7634C21.242 18.018 21.018 18.242 20.7634 18.4271C20.504 18.6155 20.0989 18.7913 19.1673 18.8923C18.1983 18.9973 16.9194 19 15 19H9C7.08058 19 5.80167 18.9973 4.83269 18.8923C3.90113 18.7913 3.49605 18.6155 3.23664 18.4271C2.98196 18.242 2.75799 18.018 2.57295 17.7634C2.38448 17.504 2.20866 17.0989 2.10773 16.1673C2.00275 15.1983 2 13.9194 2 12C2 10.0806 2.00275 8.80167 2.10773 7.83269C2.20866 6.90113 2.38448 6.49605 2.57295 6.23664C2.75799 5.98196 2.98196 5.75799 3.23664 5.57295C3.49605 5.38448 3.90113 5.20866 4.83269 5.10773C5.80167 5.00275 7.08058 5 9 5Z" fill="#ffffff"></path>
              </svg>
              <a href="https://www.instagram.com/uam.nicaragua/" target="_blank" class="hover:text-green-400 transition">Instagram</a>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-12 text-center text-sm text-white/70">
        © 2025 Universidad Americana (UAM)
      </div>
    </footer>
  </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
<script>
document.addEventListener('DOMContentLoaded', () => {
    const interBubble = document.querySelector<HTMLDivElement>('.interactive')!;
    let curX = 0;
    let curY = 0;
    let tgX = 0;
    let tgY = 0;

    function move() {
        curX += (tgX - curX) / 20;
        curY += (tgY - curY) / 20;
        interBubble.style.transform = `translate(${Math.round(curX)}px, ${Math.round(curY)}px)`;
        requestAnimationFrame(() => {
            move();
        });
    }

    window.addEventListener('mousemove', (event) => {
        tgX = event.clientX;
        tgY = event.clientY;
    });

    move();
});
</script>
<style>


:root {
 /* gradient endpoints */
  --color-bg1:           rgb(0,   141, 153);
  --color-bg2:           rgb(178, 230, 235);

  /* accent palette (evenly spaced stops) */
  --color1:              rgb(0,   141, 153);  /* 0%  */
  --color2:              rgb(44,  163, 174);  /* 25% */
  --color3:              rgb(89,  186, 194);  /* 50% */
  --color4:              rgb(134, 208, 214);  /* 75% */
  --color5:              rgb(178, 230, 235);  /* 100% */

  /* balanced interactive accent (midpoint) */
  --color-interactive:   rgb(89,  186, 194);
  --circle-size: 80%;
  --blending: hard-light;
}

@keyframes moveInCircle {
  0% {
    transform: rotate(0deg);
  }
  50% {
    transform: rotate(180deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

@keyframes moveVertical {
  0% {
    transform: translateY(-50%);
  }
  50% {
    transform: translateY(50%);
  }
  100% {
    transform: translateY(-50%);
  }
}

@keyframes moveHorizontal {
  0% {
    transform: translateX(-50%) translateY(-10%);
  }
  50% {
    transform: translateX(50%) translateY(10%);
  }
  100% {
    transform: translateX(-50%) translateY(-10%);
  }
}


.gradient-bg {
  width: 100vw;
  height: 100vh;
  position: relative;
  overflow: hidden;
  background: linear-gradient(40deg, var(--color-bg1), var(--color-bg2));
  top: 0;
  left: 0;
}

 .gradient-bg > svg {
    position: fixed;
    top:0;
    left:0;
    width: 0;
    height: 0;
  }


 .gradient-bg > .gradients-container {
    filter: url(#goo) blur(40px) ;
    width: 100%;
    height: 100%;
  }

 .gradient-bg > .g1 {
    position: absolute;
    background: radial-gradient(circle at center, rgba(var(--color1), 0.8) 0, rgba(var(--color1), 0) 50%) no-repeat;
    mix-blend-mode: var(--blending);

    width: var(--circle-size);
    height: var(--circle-size);
    top: calc(50% - var(--circle-size) / 2);
    left: calc(50% - var(--circle-size) / 2);

    transform-origin: center center;
    animation: moveVertical 30s ease infinite;

    opacity: 1;
  }

 .gradient-bg > .g2 {
    position: absolute;
    background: radial-gradient(circle at center, rgba(var(--color2), 0.8) 0, rgba(var(--color2), 0) 50%) no-repeat;
    mix-blend-mode: var(--blending);

    width: var(--circle-size);
    height: var(--circle-size);
    top: calc(50% - var(--circle-size) / 2);
    left: calc(50% - var(--circle-size) / 2);

    transform-origin: calc(50% - 400px);
    animation: moveInCircle 20s reverse infinite;

    opacity: 1;
  }

 .gradient-bg > .g3 {
    position: absolute;
    background: radial-gradient(circle at center, rgba(var(--color3), 0.8) 0, rgba(var(--color3), 0) 50%) no-repeat;
    mix-blend-mode: var(--blending);

    width: var(--circle-size);
    height: var(--circle-size);
    top: calc(50% - var(--circle-size) / 2 + 200px);
    left: calc(50% - var(--circle-size) / 2 - 500px);

    transform-origin: calc(50% + 400px);
    animation: moveInCircle 40s linear infinite;

    opacity: 1;
  }

  .gradient-bg > .g4 {
    position: absolute;
    background: radial-gradient(circle at center, rgba(var(--color4), 0.8) 0, rgba(var(--color4), 0) 50%) no-repeat;
    mix-blend-mode: var(--blending);

    width: var(--circle-size);
    height: var(--circle-size);
    top: calc(50% - var(--circle-size) / 2);
    left: calc(50% - var(--circle-size) / 2);

    transform-origin: calc(50% - 200px);
    animation: moveHorizontal 40s ease infinite;

    opacity: 0.7;
  }

     .gradient-bg > .g5 {
    position: absolute;
    background: radial-gradient(circle at center, rgba(var(--color5), 0.8) 0, rgba(var(--color5), 0) 50%) no-repeat;
    mix-blend-mode: var(--blending);

    width: calc(var(--circle-size) * 2);
    height: calc(var(--circle-size) * 2);
    top: calc(50% - var(--circle-size));
    left: calc(50% - var(--circle-size));

    transform-origin: calc(50% - 800px) calc(50% + 200px);
    animation: moveInCircle 20s ease infinite;

    opacity: 1;
  }

 .gradient-bg > .interactive {
    position: absolute;
    background: radial-gradient(circle at center, rgba(var(--color-interactive), 0.8) 0, rgba(var(--color-interactive), 0) 50%) no-repeat;
    mix-blend-mode: var(--blending);

    width: 100%;
    height: 100%;
    top: -50%;
    left: -50%;

    opacity: 0.7;
  }
</style>
    </head>
    <body class="font-sans text-gray-900 antialiased">

<div class="min-h-screen grid grid-cols-1 md:grid-cols-2 bg-white/90">
  <div class="flex items-center flex-col gap-8 justify-center p-8 bg-white bg-opacity-90">
    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
    <form method="POST" action="{{ route('login') }}" class="w-full max-w-md">
      @csrf

      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-[#008D99]">
{{__("Correo")}}
        </label>
        <input
          id="email"
          name="email"
          type="email"
          value="{{ old('email') }}"
          required
          autofocus
          autocomplete="username"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm
                 focus:border-[#008D99] focus:ring focus:ring-[#008D99] focus:ring-opacity-50"
        />
        @error('email')
          <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-[#008D99]">
{{__("Contraseña")}}
        </label>
        <input
          id="password"
          name="password"
          type="password"
          required
          autocomplete="current-password"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm
                 focus:border-[#008D99] focus:ring focus:ring-[#008D99] focus:ring-opacity-50"
        />
        @error('password')
          <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Remember Me -->
      <div class="mb-4 flex items-center">
        <input
          id="remember_me"
          name="remember"
          type="checkbox"
          class="h-4 w-4 text-[#008D99] border-gray-300 rounded focus:ring-[#008D99]"
        />
        <label for="remember_me" class="ml-2 text-sm text-[#008D99]">
            {{__("Recuerda este dispositivo")}}
        </label>
      </div>

      <!-- Actions -->
      <div class="flex flex-col gap-4 items-center justify-end space-y-4 md:space-y-0 md:space-x-4">
        <button
          type="submit"
          class="px-4 py-2 bg-[#008D99] hover:bg-[#007d89] text-white rounded-md
                 focus:outline-none focus:ring focus:ring-[#008D99] focus:ring-opacity-50"
        >
{{__("Inicia Sesión")}}
        </button>
        @if (Route::has('password.request'))
          <a
            href="{{ route('password.request') }}"
            class="text-sm text-[#008D99] underline hover:text-[#006f77]"
          >
                            {{__("Olvidaste tu contraseña?")}}
          </a>
        @endif
      </div>
    </form>
  </div>
  <div class="hidden md:block relative overflow-hidden">
    <div class="hidden md:block absolute inset-0 gradient-bg"></div>
  </div>
</div>
    </body>
</html>

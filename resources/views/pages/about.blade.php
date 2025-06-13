{{-- filepath: c:\laragon\www\Web-Design-Final-Project\resources\views\pages\about.blade.php --}}
<x-app-layout>
    <div id="about" class="relative py-24 bg-gradient-to-r from-[#31c0d3]/10 to-[#0b545b]/10 dark:from-gray-900 dark:to-gray-800 overflow-hidden select-none">
        <div class="absolute -top-16 -left-16 w-72 h-72 bg-[#31c0d3]/20 dark:bg-cyan-700/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-16 -right-16 w-96 h-96 bg-[#0b545b]/20 dark:bg-teal-600/20 rounded-full blur-3xl"></div>

        <div class="px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Título principal y un poco de contexto sobre el sistema -->
                <div class="max-w-3xl mx-auto text-center mb-16 opacity-0 transition-opacity duration-500" data-animate="animate__fadeInDown">
                    <h2 class="text-5xl sm:text-6xl font-extrabold text-[#231f20] dark:text-gray-100 mb-4">
                        Acerca de <span class="text-[#31c0d3] dark:text-cyan-400 relative inline-block font-black">Justificaciones UAM</span>
                    </h2>
                    <p class="mt-6 text-lg text-[#231f20] dark:text-gray-300 leading-relaxed">
                        El <strong>Sistema de Gestión de Justificaciones</strong> de la Universidad Americana (UAM) digitaliza y automatiza el proceso de justificación de inasistencias, la generación de reportes estadísticos y la reprogramación de evaluaciones. Con una plataforma segura, rápida e intuitiva, optimizamos la eficiencia de coordinadores, secretaría, docentes y estudiantes.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-20">
                    <!-- Mision UAM -->
                    <div class="p-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-xl transform opacity-0 transition-opacity duration-500 hover:scale-105 group" data-animate="animate__fadeInLeft">
                        <div class="flex items-center justify-center w-14 h-14 mb-4 bg-[#31c0d3] dark:bg-[#0b545b] text-white rounded-full">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-[#231f20] dark:text-gray-100 mb-2">{{ __('Misión') }}</h3>
                        <p class="text-[#231f20] dark:text-gray-300 leading-relaxed">
                            Formar líderes con visión global, emprendedores, con sólidos conocimientos científicos y principios humanísticos, capaces de aprender permanentemente para enfrentar los desafíos de la sociedad contemporánea.
                        </p>
                    </div>
                    
                    <!-- Visión UAM -->
                    <div class="p-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-xl transform opacity-0 transition-opacity duration-500 hover:scale-105 group" data-animate="animate__fadeInRight">
                        <div class="flex items-center justify-center w-14 h-14 mb-4 bg-[#31c0d3] dark:bg-[#0b545b] text-white rounded-full">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-[#231f20] dark:text-gray-100 mb-2">{{ __('Visión') }}</h3>
                        <p class="text-[#231f20] dark:text-gray-300 leading-relaxed">
                            Consolidarse como institución académica de clase internacional comprometida con el desarrollo humano equitativo y sostenible, con la eficiencia y competitividad de una organización privada de alto rendimiento.
                        </p>
                    </div>
                </div>

                <!-- Valores a destacar en la UAM -->
                @php
                    $values = [
                        ['icon' => 'fas fa-balance-scale', 'label' => 'Integridad ética y profesional'],
                        ['icon' => 'fas fa-trophy',        'label' => 'Excelencia'],
                        ['icon' => 'fas fa-user-secret',   'label' => 'Autonomía'],
                        ['icon' => 'fas fa-brain',         'label' => 'Pensamiento crítico'],
                        ['icon' => 'fas fa-handshake',     'label' => 'Respeto'],
                        ['icon' => 'fas fa-leaf',          'label' => 'Responsabilidad social y ambiental'],
                    ]
                @endphp

                <h3 class="text-3xl font-bold text-[#231f20] dark:text-gray-100 text-center mb-8 opacity-0 transition-opacity duration-500" data-animate="animate__fadeInUp">
                    Principios que nos guían
                </h3>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-8 opacity-0 transition-opacity duration-500" data-animate="animate__zoomIn">
                    @foreach ($values as $value)
                        <div class="flex flex-col items-center p-6 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl shadow-md transform transition hover:scale-110 group">
                            <div class="flex items-center justify-center w-12 h-12 mb-3 bg-[#31c0d3] dark:bg-[#0b545b] text-white rounded-full">
                                <i class="{{ $value['icon'] }}"></i>
                            </div>
                            <p class="text-sm font-medium text-[#231f20] dark:text-gray-200 text-center">
                                {{ $value['label'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
  /**
   * Se observan elementos con [data-animate] y luego se disparan sus animaciones
   * (animate.css) al entrar en el viewport (parte visible actual de la página por el usuario).
   * 
   * Basado en Intersection Observer API:
   * https://developer.mozilla.org/docs/Web/API/Intersection_Observer_API
   */
  document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return; // Si el elemento no está lo bastante visible, se ignora

        // Se quita la opacidad del componente y se añaden las clases de animate.css para disparar la animación
        entry.target.classList.remove('opacity-0');
        entry.target.classList.add(
          'animate__animated',
          entry.target.dataset.animate
        );
        
        // Como solo se animará una vez, dejamos de observar este elemento
        observer.unobserve(entry.target);
      });
    }, { threshold: 0.5 });  // Se dispara cuando el 50% del elemento esté visible

    // Se observan todos los elementos marcados con data-animate
    document.querySelectorAll('[data-animate]').forEach(el =>
      observer.observe(el)
    );
  });
</script>
@endpush
</x-app-layout>
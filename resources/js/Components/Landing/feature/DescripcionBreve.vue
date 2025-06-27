<template>
  <section class="py-12 md:py-16 px-4 sm:px-6 lg:px-8 ">
    <div class="max-w-7xl mx-auto">
      <div class="grid lg:grid-cols-2 gap-8 md:gap-12 items-center">
        <!-- Columna izquierda: Lista de características -->
        <div class="space-y-6 md:space-y-8">
          <h2 class="text-3xl sm:text-4xl font-black text-gray-800">
            <span class="block">Gestión Integral</span>
            <span class="text-blue-700 font-light text-2xl sm:text-3xl block mt-2">
              Todo lo que necesitas en un solo lugar
            </span>
          </h2>

          <div class="space-y-4 md:space-y-6">
            <!-- Item 1 -->
            <div 
              ref="feature1"
              class=" p-5 rounded-2xl shadow-lg"
            >
              <div class="flex items-start space-x-4">
                <div class="bg-blue-100/50 p-3 rounded-xl shadow-inner">
                  <UserGroupIcon class="w-6 h-6 text-blue-700" />
                </div>
                <div>
                  <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">Registro Completo de Estudiantes</h3>
                  <p class="text-gray-600">
                    Gestión centralizada de datos, diagnósticos y documentos legales con validación automática según normativa.
                  </p>
                </div>
              </div>
            </div>

            <!-- Item 2 -->
            <div 
              ref="feature2"
              class=" p-5 rounded-2xl shadow-lg"
            >
              <div class="flex items-start space-x-4">
                <div class="bg-blue-100/50 p-3 rounded-xl shadow-inner">
                  <DocumentTextIcon class="w-6 h-6 text-blue-700" />
                </div>
                <div>
                  <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">Planes de Apoyo Individualizados</h3>
                  <p class="text-gray-600">
                    Creación, seguimiento y evaluación de PAI con plantillas MINEDUC e historial de versiones.
                  </p>
                </div>
              </div>
            </div>

            <!-- Item 3 -->
            <div 
              ref="feature3"
              class="p-5 rounded-2xl shadow-lg"
            >
              <div class="flex items-start space-x-4">
                <div class="bg-blue-100/50 p-3 rounded-xl shadow-inner">
                  <CalendarIcon class="w-6 h-6 text-blue-700" />
                </div>
                <div>
                  <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">Sesiones Programadas</h3>
                  <p class="text-gray-600">
                    Planificación y registro detallado de intervenciones con alertas tempranas y control de cobertura.
                  </p>
                </div>
              </div>
            </div>

            <!-- Item 4 -->
            <div 
              ref="feature4"
              class=" p-5 rounded-2xl shadow-lg"
            >
              <div class="flex items-start space-x-4">
                <div class="bg-blue-100/50 p-3 rounded-xl shadow-inner">
                  <ChartBarIcon class="w-6 h-6 text-blue-700" />
                </div>
                <div>
                  <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">Reportes Automatizados</h3>
                  <p class="text-gray-600">
                    Generación de informes para rendición PIE con estadísticas predictivas y proyecciones.
                  </p>
                </div>
              </div>
            </div>

            <!-- Item 5 -->
            <div 
              ref="feature5"
              class=" p-5 rounded-2xl shadow-lg"
            >
              <div class="flex items-start space-x-4">
                <div class="bg-blue-100/50 p-3 rounded-xl shadow-inner">
                  <ShieldCheckIcon class="w-6 h-6 text-blue-700" />
                </div>
                <div>
                  <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">Seguridad y Cumplimiento</h3>
                  <p class="text-gray-600">
                    Cifrado de datos, autenticación segura y cumplimiento normativo para protección de información.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Columna derecha: Imagen del teléfono -->
        <div class="relative flex justify-center order-first lg:order-last">
          <div class="max-w-xs w-full">
            <img 
              src="/img/phone.svg" 
              alt="App DiverMind mostrando información del alumno" 
              class="w-full h-auto rounded-3xl shadow-xl"
              ref="phoneImage"
            />
            <div class="absolute -bottom-4 -right-4 z-10  px-3 py-1.5 rounded-full shadow-md">
              <div class="flex items-center">
                <BellIcon class="w-5 h-5 text-blue-500 mr-2" />
                <span class="text-gray-800 font-medium text-sm">Notificaciones en tiempo real</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { gsap } from 'gsap';
import { 
  BellIcon,
  UserGroupIcon,
  DocumentTextIcon,
  CalendarIcon,
  ChartBarIcon,
  ShieldCheckIcon
} from '@heroicons/vue/24/outline';

const phoneImage = ref(null);
const feature1 = ref(null);
const feature2 = ref(null);
const feature3 = ref(null);
const feature4 = ref(null);
const feature5 = ref(null);

onMounted(() => {
  // Animación para la imagen del teléfono
  gsap.from(phoneImage.value, {
    duration: 1,
    y: 50,
    opacity: 0,
    ease: "power3.out",
    delay: 0.3
  });

  // Animación para las características
  const features = [
    feature1.value,
    feature2.value,
    feature3.value,
    feature4.value,
    feature5.value
  ];

  features.forEach((feature, index) => {
    gsap.from(feature, {
      duration: 0.8,
      y: 20,
      opacity: 0,
      ease: "back.out(1.7)",
      delay: 0.5 + (index * 0.15)
    });
  });
});
</script>

<style scoped>
/* Estilos para mejorar la legibilidad */
.bg-gray-50 {
  background-color: #f9fafb;
}

.text-gray-800 {
  color: #1f2937;
}

.text-gray-600 {
  color: #4b5563;
}

.text-blue-700 {
  color: #1d4ed8;
}

/* Clase corregida con opacidad */
.bg-blue-100-opacity {
  background-color: rgba(219, 234, 254, 0.5);
}

/* Efectos de hover */
.bg-white {
  transition: all 0.3s ease;
}

.bg-white:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

/* Responsive */
@media (max-width: 1024px) {
  .max-w-xs {
    max-width: 300px;
  }
}

@media (max-width: 768px) {
  .p-5 {
    padding: 1rem;
  }

  .text-lg {
    font-size: 1rem;
    line-height: 1.5rem;
  }
}
</style>
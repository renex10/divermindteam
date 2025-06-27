<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const isOpen = ref(false);

// Usar window.route para asegurar disponibilidad de Ziggy
const menuItems = [
  { name: 'Inicio', route: 'home', isRoute: true },
  { name: '¿Qué es?', route: 'que-es', isRoute: true },
  { name: 'Características', route: 'caracteristicas', isRoute: true },
  { name: 'Beneficios', route: 'beneficios', isRoute: true },
  { name: 'Normativa', route: 'normativa', isRoute: true },
  { name: 'Integraciones', route: 'integraciones', isRoute: true },
  { name: 'Contacto', href: '#contacto', isRoute: false }
];

// Métodos para menú móvil
const toggleMobileMenu = () => {
  isOpen.value = !isOpen.value;
};

const closeMobileMenu = () => {
  isOpen.value = false;
};

// Función para manejar clics en enlaces ancla
const scrollToSection = (sectionId) => {
  closeMobileMenu();
  
  requestAnimationFrame(() => {
    const target = document.querySelector(sectionId);
    if (target) {
      window.scrollTo({
        top: target.offsetTop,
        behavior: 'smooth'
      });
    }
  });
};
</script>

<template>
  <nav class="sticky top-0 z-50 px-4 py-3 transition-all duration-300 ease-in-out backdrop-blur-sm" 
       style="background-color: #16425b; box-shadow: 0 8px 32px rgba(22, 66, 91, 0.3), inset 0 2px 4px rgba(255, 255, 255, 0.1), inset 0 -2px 4px rgba(0, 0, 0, 0.2);">
    <div class="max-w-7xl mx-auto">
      <div class="flex items-center justify-between">
        
        <!-- Logo Section -->
        <Link :href="route('home')" class="flex items-center space-x-3 group cursor-pointer">
          <div class="relative p-2 rounded-xl transition-all duration-300" 
               style="background: linear-gradient(145deg, #1a4a66, #12394f); box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.3), -3px -3px 8px rgba(255, 255, 255, 0.05);">
            <svg class="w-6 h-6 text-current transition-colors duration-300" 
                 style="color: #81c3d7;" 
                 fill="currentColor" 
                 viewBox="0 0 24 24">
              <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
          </div>
          <h1 class="text-xl font-bold tracking-wide transition-colors duration-300 group-hover:text-opacity-90" 
              style="color: #d9dcd6; font-family: 'Inter', 'SF Pro Display', system-ui, sans-serif;">
            DIVERMIND
          </h1>
        </Link>

        <!-- Desktop Menu -->
        <div class="hidden lg:flex items-center space-x-1">
          <div class="flex items-center space-x-1 px-3 py-2 rounded-full" 
               style="background: linear-gradient(145deg, #1a4a66, #12394f); box-shadow: inset 2px 2px 6px rgba(0, 0, 0, 0.3), inset -2px -2px 6px rgba(255, 255, 255, 0.05);">
            
            <template v-for="item in menuItems" :key="item.name">
              <!-- Rutas de Inertia -->
              <Link v-if="item.isRoute"
                 :href="route(item.route)"
                 class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#81c3d7] focus:ring-opacity-50 relative overflow-hidden group"
                 style="color: #d9dcd6;">
                 
                <span class="relative z-10 transition-colors duration-300 group-hover:text-white">
                  {{ item.name }}
                </span>
                
                <div class="absolute inset-0 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-95 group-hover:scale-100" 
                     style="background: linear-gradient(145deg, #2f6690, #26567a); box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2), -1px -1px 3px rgba(255, 255, 255, 0.05);"></div>
              </Link>
              
              <!-- Enlaces ancla -->
              <a v-else
                 :href="item.href"
                 @click.prevent="scrollToSection(item.href)"
                 class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#81c3d7] focus:ring-opacity-50 relative overflow-hidden group"
                 style="color: #d9dcd6;">
                 
                <span class="relative z-10 transition-colors duration-300 group-hover:text-white">
                  {{ item.name }}
                </span>
                
                <div class="absolute inset-0 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-95 group-hover:scale-100" 
                     style="background: linear-gradient(145deg, #2f6690, #26567a); box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2), -1px -1px 3px rgba(255, 255, 255, 0.05);"></div>
              </a>
            </template>
          </div>

          <!-- Login Button -->
          <div class="ml-4">
            <Link 
              :href="route('login')"
              class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-300 ease-in-out transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#81c3d7] focus:ring-opacity-50 shadow-lg relative overflow-hidden group"
              style="background: linear-gradient(135deg, #16425b 0%, #81c3d7 100%); box-shadow: 0 4px 15px rgba(129, 195, 215, 0.3), inset 0 1px 2px rgba(255, 255, 255, 0.2);">
              
              <span class="relative z-10 transition-all duration-300 group-hover:text-opacity-95">
                Ingresar
              </span>
            </Link>
          </div>
        </div>

        <!-- Mobile Menu Button -->
        <div class="lg:hidden">
          <button @click="toggleMobileMenu"
                  class="p-3 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#81c3d7] focus:ring-opacity-50"
                  style="background: linear-gradient(145deg, #1a4a66, #12394f); box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.3), -3px -3px 8px rgba(255, 255, 255, 0.05);"
                  :aria-expanded="isOpen"
                  aria-label="Toggle navigation menu">
            
            <svg class="w-6 h-6 transition-all duration-300" 
                 style="color: #d9dcd6;"
                 :class="{ 'rotate-90': isOpen }"
                 fill="currentColor" 
                 viewBox="0 0 24 24">
              <path v-if="!isOpen" d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path v-else d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 transform -translate-y-4 scale-95"
        enter-to-class="opacity-100 transform translate-y-0 scale-100"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 transform translate-y-0 scale-100"
        leave-to-class="opacity-0 transform -translate-y-4 scale-95">
        
        <div v-show="isOpen" 
             class="lg:hidden mt-4 py-4 px-2 rounded-2xl backdrop-blur-sm"
             style="background: linear-gradient(145deg, #1a4a66, #12394f); box-shadow: inset 2px 2px 8px rgba(0, 0, 0, 0.3), inset -2px -2px 8px rgba(255, 255, 255, 0.05), 0 8px 32px rgba(0, 0, 0, 0.2);">
          
          <div class="space-y-2">
            <template v-for="item in menuItems" :key="item.name">
              <Link v-if="item.isRoute"
                 :href="route(item.route)"
                 @click="closeMobileMenu"
                 class="block px-4 py-3 rounded-xl text-base font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#81c3d7] focus:ring-opacity-50 relative overflow-hidden group"
                 style="color: #d9dcd6;">
                 
                <span class="relative z-10 transition-colors duration-300 group-hover:text-white group-active:text-white">
                  {{ item.name }}
                </span>
              </Link>
              
              <a v-else
                 :href="item.href"
                 @click.prevent="scrollToSection(item.href)"
                 class="block px-4 py-3 rounded-xl text-base font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#81c3d7] focus:ring-opacity-50 relative overflow-hidden group"
                 style="color: #d9dcd6;">
                 
                <span class="relative z-10 transition-colors duration-300 group-hover:text-white group-active:text-white">
                  {{ item.name }}
                </span>
              </a>
            </template>
          </div>

          <div class="mt-6 pt-4 border-t border-opacity-20" style="border-color: #d9dcd6;">
            <Link 
              :href="route('login')"
              @click="closeMobileMenu"
              class="w-full px-6 py-3 rounded-xl text-base font-semibold text-white transition-all duration-300 ease-in-out transform active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#81c3d7] focus:ring-opacity-50 shadow-lg relative overflow-hidden group"
              style="background: linear-gradient(135deg, #16425b 0%, #81c3d7 100%); box-shadow: 0 4px 15px rgba(129, 195, 215, 0.3), inset 0 1px 2px rgba(255, 255, 255, 0.2);">
              
              <span class="relative z-10 transition-all duration-300 group-active:text-opacity-95">
                Ingresar
              </span>
            </Link>
          </div>
        </div>
      </Transition>
    </div>
  </nav>
</template>

<style scoped>
/* Additional custom styles */
.backdrop-blur-sm {
  backdrop-filter: blur(4px);
}

/* Smooth font rendering */
* {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* Focus styles for better accessibility */
button:focus-visible,
a:focus-visible {
  outline: 2px solid #81c3d7;
  outline-offset: 2px;
}

/* Custom scrollbar for consistency */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #16425b;
}

::-webkit-scrollbar-thumb {
  background: #81c3d7;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #2f6690;
}

/* Transition for mobile menu */
.v-enter-active,
.v-leave-active {
  transition: opacity 0.3s, transform 0.3s;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Focus ring color */
.focus\:ring-\[\#81c3d7\]:focus {
  --tw-ring-color: #81c3d7;
}
</style>
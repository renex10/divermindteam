<template>
  <section class="py-20 px-4 sm:px-6 lg:px-8 bg-background min-h-screen">
    <div class="max-w-7xl mx-auto">
      <!-- Hero Section -->
      <div class="text-center mb-20">
        <div class="neo-badge mb-8">
          <span class="text-primary font-medium">¿Qué es nuestro sistema?</span>
        </div>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-light text-dark mb-8 leading-tight">
          Plataforma Integral
          <span class="text-primary font-medium block mt-2">PIE Digital</span>
        </h1>
        <p class="text-xl sm:text-2xl text-dark/70 max-w-4xl mx-auto font-light leading-relaxed">
          Una solución tecnológica avanzada diseñada específicamente para optimizar 
          la gestión de Programas de Integración Escolar en Chile
        </p>
      </div>

      <!-- Main Content Grid -->
      <div class="grid lg:grid-cols-2 gap-16 items-center mb-20">
        <!-- Left Column - Main Description -->
        <div class="space-y-8">
          <div class="neo-content-card">
            <div class="neo-icon-highlight mb-6">
              <CogIcon class="w-12 h-12 text-accent" />
            </div>
            <h2 class="text-2xl sm:text-3xl font-medium text-dark mb-6">
              Tecnología al Servicio de la Educación Inclusiva
            </h2>
            <p class="text-dark/80 text-lg leading-relaxed mb-6">
              Nuestro sistema representa una evolución en la gestión educativa especializada, 
              combinando las mejores prácticas pedagógicas con tecnología de vanguardia para 
              crear un ecosistema digital que potencia el trabajo de profesionales PIE.
            </p>
            <p class="text-dark/70 leading-relaxed">
              Desarrollado en colaboración con educadores diferenciales, psicopedagogos y 
              especialistas en necesidades educativas especiales, garantizando que cada 
              funcionalidad responda a desafíos reales del aula.
            </p>
          </div>
        </div>

        <!-- Right Column - Key Benefits -->
        <div class="space-y-6">
          <div 
            v-for="(benefit, index) in keyBenefits" 
            :key="index"
            class="neo-benefit-card group"
            :class="{ 'active': activeBenefit === index }"
            @mouseenter="activeBenefit = index"
            @mouseleave="activeBenefit = null"
          >
            <div class="flex items-start space-x-4">
              <div class="neo-mini-icon">
                <component :is="benefit.icon" class="w-6 h-6 text-primary" />
              </div>
              <div class="flex-1">
                <h3 class="text-lg font-medium text-dark mb-2">{{ benefit.title }}</h3>
                <p class="text-dark/70 text-sm leading-relaxed">{{ benefit.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Vision Section -->
      <div class="mb-20">
        <div class="neo-vision-card text-center">
          <div class="neo-vision-icon mb-8">
            <LightBulbIcon class="w-16 h-16 text-accent mx-auto" />
          </div>
          <h2 class="text-3xl sm:text-4xl font-light text-dark mb-6">
            Nuestra Visión
          </h2>
          <p class="text-xl text-dark/80 max-w-4xl mx-auto leading-relaxed mb-8">
            Ser la plataforma líder en Chile para la gestión de Programas de Integración Escolar, 
            transformando la manera en que los establecimientos educacionales abordan la inclusión 
            y el apoyo a estudiantes con necesidades educativas especiales.
          </p>
          <div class="grid sm:grid-cols-3 gap-8 mt-12">
            <div class="neo-stat-card">
              <div class="text-3xl font-light text-primary mb-2">100%</div>
              <div class="text-dark/70 text-sm">Cumplimiento Normativo</div>
            </div>
            <div class="neo-stat-card">
              <div class="text-3xl font-light text-accent mb-2">24/7</div>
              <div class="text-dark/70 text-sm">Disponibilidad del Sistema</div>
            </div>
            <div class="neo-stat-card">
              <div class="text-3xl font-light text-primary-light mb-2">∞</div>
              <div class="text-dark/70 text-sm">Capacidad de Crecimiento</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Technology Stack -->
      <div class="mb-20">
        <h2 class="text-3xl font-light text-center text-dark mb-12">
          Tecnología de Vanguardia
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div 
            v-for="(tech, index) in technologies" 
            :key="index"
            class="neo-tech-card group"
          >
            <div class="neo-tech-icon mb-4">
              <component :is="tech.icon" class="w-8 h-8 text-primary" />
            </div>
            <h3 class="text-sm font-medium text-dark mb-2">{{ tech.name }}</h3>
            <p class="text-xs text-dark/60">{{ tech.description }}</p>
          </div>
        </div>
      </div>

      <!-- Call to Action -->
      <div class="text-center">
        <div class="neo-cta-card">
          <h2 class="text-2xl sm:text-3xl font-light text-dark mb-6">
            ¿Listo para Transformar tu Gestión PIE?
          </h2>
          <p class="text-dark/70 mb-8 max-w-2xl mx-auto leading-relaxed">
            Únete a los establecimientos educacionales que ya están revolucionando 
            su trabajo con estudiantes con necesidades educativas especiales.
          </p>
          <button class="neo-cta-button group">
            <span class="relative z-10">Solicitar Demostración</span>
            <ArrowRightIcon class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-200" />
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { 
  CogIcon,
  LightBulbIcon,
  ShieldCheckIcon,
  ClockIcon,
  ChartBarIcon,
  CloudIcon,
  DevicePhoneMobileIcon,
  CpuChipIcon,
  ArrowRightIcon
} from '@heroicons/vue/24/outline'

// Estado reactivo
const activeBenefit = ref(null)

// Beneficios clave
const keyBenefits = [
  {
    icon: ShieldCheckIcon,
    title: "Cumplimiento Normativo Garantizado",
    description: "Automatiza el cumplimiento del Decreto 170 y normativas MINEDUC, eliminando riesgos de incumplimiento y multas."
  },
  {
    icon: ClockIcon,
    title: "Ahorro de Tiempo Significativo",
    description: "Reduce hasta un 70% el tiempo dedicado a tareas administrativas, permitiendo mayor foco en el trabajo pedagógico."
  },
  {
    icon: ChartBarIcon,
    title: "Toma de Decisiones Basada en Datos",
    description: "Analytics avanzados y reportes inteligentes para optimizar recursos y mejorar resultados estudiantiles."
  }
]

// Stack tecnológico
const technologies = [
  {
    icon: CloudIcon,
    name: "Cloud Computing",
    description: "Infraestructura escalable"
  },
  {
    icon: ShieldCheckIcon,
    name: "Seguridad Avanzada",
    description: "Protección de datos"
  },
  {
    icon: DevicePhoneMobileIcon,
    name: "Responsive Design",
    description: "Acceso multiplataforma"
  },
  {
    icon: CpuChipIcon,
    name: "IA Integrada",
    description: "Inteligencia predictiva"
  }
]
</script>

<style scoped>
/* Componentes base neomórficos */
.neo-badge {
  @apply inline-flex px-6 py-3 rounded-full text-sm;
  background: #D9DCD6;
  box-shadow: 
    inset 4px 4px 8px rgba(22, 66, 91, 0.1),
    inset -4px -4px 8px rgba(255, 255, 255, 0.8);
}

.neo-content-card {
  @apply p-8 rounded-3xl;
  background: #D9DCD6;
  box-shadow: 
    12px 12px 24px rgba(22, 66, 91, 0.15),
    -12px -12px 24px rgba(255, 255, 255, 0.7);
}

.neo-icon-highlight {
  @apply inline-flex p-4 rounded-2xl;
  background: #D9DCD6;
  box-shadow: 
    inset 6px 6px 12px rgba(22, 66, 91, 0.1),
    inset -6px -6px 12px rgba(255, 255, 255, 0.8);
}

.neo-benefit-card {
  @apply p-6 rounded-2xl transition-all duration-300 cursor-pointer;
  background: #D9DCD6;
  box-shadow: 
    8px 8px 16px rgba(22, 66, 91, 0.1),
    -8px -8px 16px rgba(255, 255, 255, 0.6);
}

.neo-benefit-card:hover,
.neo-benefit-card.active {
  transform: translateY(-2px);
  box-shadow: 
    12px 12px 24px rgba(22, 66, 91, 0.15),
    -12px -12px 24px rgba(255, 255, 255, 0.7);
}

.neo-mini-icon {
  @apply p-2 rounded-xl flex-shrink-0;
  background: #D9DCD6;
  box-shadow: 
    inset 3px 3px 6px rgba(22, 66, 91, 0.1),
    inset -3px -3px 6px rgba(255, 255, 255, 0.8);
}

.neo-vision-card {
  @apply p-12 rounded-3xl;
  background: #D9DCD6;
  box-shadow: 
    16px 16px 32px rgba(22, 66, 91, 0.15),
    -16px -16px 32px rgba(255, 255, 255, 0.7);
}

.neo-vision-icon {
  @apply inline-flex p-6 rounded-3xl;
  background: #D9DCD6;
  box-shadow: 
    inset 8px 8px 16px rgba(22, 66, 91, 0.1),
    inset -8px -8px 16px rgba(255, 255, 255, 0.8);
}

.neo-stat-card {
  @apply p-6 rounded-2xl text-center;
  background: #D9DCD6;
  box-shadow: 
    inset 6px 6px 12px rgba(22, 66, 91, 0.1),
    inset -6px -6px 12px rgba(255, 255, 255, 0.8);
}

.neo-tech-card {
  @apply p-6 rounded-2xl text-center transition-all duration-300 cursor-pointer;
  background: #D9DCD6;
  box-shadow: 
    8px 8px 16px rgba(22, 66, 91, 0.1),
    -8px -8px 16px rgba(255, 255, 255, 0.6);
}

.neo-tech-card:hover {
  transform: translateY(-2px);
  box-shadow: 
    12px 12px 24px rgba(22, 66, 91, 0.15),
    -12px -12px 24px rgba(255, 255, 255, 0.7);
}

.neo-tech-icon {
  @apply p-3 rounded-xl mx-auto;
  background: #D9DCD6;
  box-shadow: 
    inset 4px 4px 8px rgba(22, 66, 91, 0.1),
    inset -4px -4px 8px rgba(255, 255, 255, 0.8);
}

.neo-cta-card {
  @apply p-12 rounded-3xl;
  background: #D9DCD6;
  box-shadow: 
    16px 16px 32px rgba(22, 66, 91, 0.15),
    -16px -16px 32px rgba(255, 255, 255, 0.7);
}

.neo-cta-button {
  @apply inline-flex items-center px-8 py-4 rounded-2xl font-medium text-white transition-all duration-300;
  background: linear-gradient(135deg, #2F6690, #3A7CA5);
  box-shadow: 
    8px 8px 16px rgba(22, 66, 91, 0.3),
    -2px -2px 8px rgba(255, 255, 255, 0.2);
}

.neo-cta-button:hover {
  transform: translateY(-2px);
  box-shadow: 
    12px 12px 24px rgba(22, 66, 91, 0.4),
    -4px -4px 12px rgba(255, 255, 255, 0.3);
}

.neo-cta-button:active {
  transform: translateY(0);
  box-shadow: 
    4px 4px 8px rgba(22, 66, 91, 0.3),
    -1px -1px 4px rgba(255, 255, 255, 0.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .neo-content-card,
  .neo-vision-card,
  .neo-cta-card {
    @apply p-6;
  }
  
  .neo-benefit-card {
    @apply p-4;
  }
  
  .neo-tech-card {
    @apply p-4;
  }
}

/* Animaciones sutiles */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-5px); }
}

.neo-vision-icon {
  animation: float 6s ease-in-out infinite;
}

/* Accesibilidad */
@media (prefers-reduced-motion: reduce) {
  .neo-vision-icon {
    animation: none;
  }
  
  .neo-benefit-card,
  .neo-tech-card,
  .neo-cta-button {
    transition: none;
  }
  
  .neo-benefit-card:hover,
  .neo-tech-card:hover,
  .neo-cta-button:hover {
    transform: none;
  }
}

.neo-cta-button:focus-visible,
.neo-benefit-card:focus-visible,
.neo-tech-card:focus-visible {
  outline: 2px solid #3A7CA5;
  outline-offset: 2px;
}
</style>
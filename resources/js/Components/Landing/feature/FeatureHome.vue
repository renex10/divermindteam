<template>
  <section class="py-16 px-4 sm:px-6 lg:px-8 bg-background min-h-screen">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section -->
      <div class="text-center mb-16">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-light text-primary mb-6 tracking-wide">
          Sistema Integral PIE
        </h2>
        <p class="text-lg sm:text-xl text-dark/70 max-w-3xl mx-auto font-light leading-relaxed">
          Plataforma completa para la gestión de Programas de Integración Escolar, 
          diseñada para optimizar el apoyo a estudiantes con Necesidades Educativas Especiales
        </p>
      </div>

      <!-- Features Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 lg:gap-10">
        <!-- Feature 1: Gestión de Estudiantes PIE -->
        <div 
          class="neo-card group cursor-pointer"
          @mouseenter="hoveredCard = 1"
          @mouseleave="hoveredCard = null"
        >
          <div class="neo-icon-container">
            <AcademicCapIcon class="neo-icon text-primary" />
          </div>
          <h3 class="neo-title">Gestión de Estudiantes PIE</h3>
          <p class="neo-description">
            Digitaliza el registro de estudiantes con NEE y toda su documentación. 
            Automatiza controles legales (Decreto 170, vigencias) y asocia cada estudiante 
            a un plan PAI con seguimiento personalizado.
          </p>
          <div class="neo-highlight" :class="{ 'active': hoveredCard === 1 }"></div>
        </div>

        <!-- Feature 2: Gestión y Seguimiento de PAI -->
        <div 
          class="neo-card group cursor-pointer"
          @mouseenter="hoveredCard = 2"
          @mouseleave="hoveredCard = null"
        >
          <div class="neo-icon-container">
            <ChartBarIcon class="neo-icon text-accent" />
          </div>
          <h3 class="neo-title">Gestión y Seguimiento de PAI</h3>
          <p class="neo-description">
            Construye, edita y versiona planes de apoyo por estudiante. 
            Monitorea objetivos pedagógicos, progresos y adecuaciones curriculares 
            oficiales con reportes compatibles MINEDUC.
          </p>
          <div class="neo-highlight" :class="{ 'active': hoveredCard === 2 }"></div>
        </div>

        <!-- Feature 3: Profesionales PIE -->
        <div 
          class="neo-card group cursor-pointer"
          @mouseenter="hoveredCard = 3"
          @mouseleave="hoveredCard = null"
        >
          <div class="neo-icon-container">
            <UsersIcon class="neo-icon text-primary-light" />
          </div>
          <h3 class="neo-title">Profesionales PIE</h3>
          <p class="neo-description">
            Registra educadores diferenciales y psicopedagogos. 
            Automatiza planificación de sesiones, distribución de carga horaria 
            y recursos con algoritmos de priorización para casos complejos.
          </p>
          <div class="neo-highlight" :class="{ 'active': hoveredCard === 3 }"></div>
        </div>

        <!-- Feature 4: Sesiones de Atención -->
        <div 
          class="neo-card group cursor-pointer"
          @mouseenter="hoveredCard = 4"
          @mouseleave="hoveredCard = null"
        >
          <div class="neo-icon-container">
            <CalendarDaysIcon class="neo-icon text-dark" />
          </div>
          <h3 class="neo-title">Sesiones de Atención</h3>
          <p class="neo-description">
            Planifica sesiones individuales o grupales. Registra asistencia, 
            evidencia y contenido trabajado. Emite reportes mensuales con alertas 
            sobre PAI vencidos o faltas de atención.
          </p>
          <div class="neo-highlight" :class="{ 'active': hoveredCard === 4 }"></div>
        </div>

        <!-- Feature 5: Trabajo Colaborativo -->
        <div 
          class="neo-card group cursor-pointer"
          @mouseenter="hoveredCard = 5"
          @mouseleave="hoveredCard = null"
        >
          <div class="neo-icon-container">
            <UserGroupIcon class="neo-icon text-accent" />
          </div>
          <h3 class="neo-title">Trabajo Colaborativo</h3>
          <p class="neo-description">
            Facilita la co-planificación entre docentes y profesionales PIE. 
            Incluye comentarios, calendario escolar MINEDUC y notificaciones 
            de reuniones para trabajo en equipo efectivo.
          </p>
          <div class="neo-highlight" :class="{ 'active': hoveredCard === 5 }"></div>
        </div>

        <!-- Feature 6: Informes y Reportes -->
        <div 
          class="neo-card group cursor-pointer"
          @mouseenter="hoveredCard = 6"
          @mouseleave="hoveredCard = null"
        >
          <div class="neo-icon-container">
            <DocumentChartBarIcon class="neo-icon text-primary" />
          </div>
          <h3 class="neo-title">Informes y Reportes</h3>
          <p class="neo-description">
            Genera informes periódicos y consolidados por estudiante, profesional 
            o establecimiento. Exporta en PDF/Excel y automatiza rendiciones 
            con inteligencia predictiva integrada.
          </p>
          <div class="neo-highlight" :class="{ 'active': hoveredCard === 6 }"></div>
        </div>

        <!-- Feature 7: Gestión Documental -->
        <div 
          class="neo-card group cursor-pointer"
          @mouseenter="hoveredCard = 7"
          @mouseleave="hoveredCard = null"
        >
          <div class="neo-icon-container">
            <FolderIcon class="neo-icon text-primary-light" />
          </div>
          <h3 class="neo-title">Gestión Documental</h3>
          <p class="neo-description">
            Almacena documentos clave por usuario o sesión. Controla versiones, 
            descargas, seguridad y trazabilidad. Se integra con plataformas 
            oficiales como SIGE o SEP.
          </p>
          <div class="neo-highlight" :class="{ 'active': hoveredCard === 7 }"></div>
        </div>

        <!-- Feature 8: Administración -->
        <div 
          class="neo-card group cursor-pointer"
          @mouseenter="hoveredCard = 8"
          @mouseleave="hoveredCard = null"
        >
          <div class="neo-icon-container">
            <ShieldCheckIcon class="neo-icon text-dark" />
          </div>
          <h3 class="neo-title">Administración</h3>
          <p class="neo-description">
            Crea usuarios con roles específicos y acceso granular. 
            Lleva bitácora de acciones (auditoría) y proporciona acceso 
            en solo lectura a MINEDUC para supervisión oficial.
          </p>
          <div class="neo-highlight" :class="{ 'active': hoveredCard === 8 }"></div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { 
  AcademicCapIcon,
  ChartBarIcon,
  UsersIcon,
  CalendarDaysIcon,
  UserGroupIcon,
  DocumentChartBarIcon,
  FolderIcon,
  ShieldCheckIcon
} from '@heroicons/vue/24/outline'

// Estado reactivo para el hover
const hoveredCard = ref(null)
</script>

<style scoped>
/* Estilos base neomórficos */
.neo-card {
  @apply relative p-8 rounded-3xl transition-all duration-300 ease-in-out;
  background: #D9DCD6;
  box-shadow: 
    12px 12px 24px rgba(22, 66, 91, 0.15),
    -12px -12px 24px rgba(255, 255, 255, 0.7);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.neo-card:hover {
  transform: translateY(-2px);
  box-shadow: 
    16px 16px 32px rgba(22, 66, 91, 0.2),
    -16px -16px 32px rgba(255, 255, 255, 0.8);
}

.neo-card:active {
  transform: translateY(1px);
  box-shadow: 
    8px 8px 16px rgba(22, 66, 91, 0.2),
    -8px -8px 16px rgba(255, 255, 255, 0.6);
}

.neo-icon-container {
  @apply mb-6 p-4 rounded-2xl inline-flex;
  background: #D9DCD6;
  box-shadow: 
    inset 6px 6px 12px rgba(22, 66, 91, 0.1),
    inset -6px -6px 12px rgba(255, 255, 255, 0.8);
}

.neo-icon {
  @apply w-8 h-8;
}

.neo-title {
  @apply text-xl font-medium text-dark mb-4 leading-tight;
  font-weight: 500;
}

.neo-description {
  @apply text-dark/70 leading-relaxed font-light;
  font-size: 0.95rem;
  line-height: 1.6;
}

.neo-highlight {
  @apply absolute inset-0 rounded-3xl opacity-0 transition-opacity duration-300;
  background: linear-gradient(135deg, rgba(129, 195, 215, 0.1), rgba(58, 124, 165, 0.1));
  pointer-events: none;
}

.neo-highlight.active {
  @apply opacity-100;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .neo-card {
    @apply p-6;
  }
  
  .neo-icon-container {
    @apply p-3;
  }
  
  .neo-icon {
    @apply w-6 h-6;
  }
  
  .neo-title {
    @apply text-lg;
  }
  
  .neo-description {
    font-size: 0.9rem;
  }
}

@media (max-width: 480px) {
  .neo-card {
    @apply p-5;
  }
  
  .neo-title {
    @apply text-base mb-3;
  }
  
  .neo-description {
    font-size: 0.85rem;
  }
}

/* Animación sutil para mejor UX */
@keyframes subtle-pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.02);
  }
}

.neo-card:hover .neo-icon-container {
  animation: subtle-pulse 2s infinite;
}

/* Mejoras de accesibilidad */
.neo-card:focus {
  outline: 2px solid #3A7CA5;
  outline-offset: 2px;
}

.neo-card:focus-visible {
  outline: 2px solid #3A7CA5;
  outline-offset: 2px;
}

/* Soporte para prefers-reduced-motion */
@media (prefers-reduced-motion: reduce) {
  .neo-card,
  .neo-highlight,
  .neo-icon-container {
    transition: none;
  }
  
  .neo-card:hover .neo-icon-container {
    animation: none;
  }
  
  .neo-card:hover {
    transform: none;
  }
  
  .neo-card:active {
    transform: none;
  }
}
</style>
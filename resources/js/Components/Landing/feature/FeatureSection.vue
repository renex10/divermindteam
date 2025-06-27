<template>
  <section class="py-20 px-4 sm:px-6 lg:px-8 bg-background">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section -->
      <div class="text-center mb-20">
        <div class="neo-badge mb-8">
          <span class="text-primary font-medium">Características Avanzadas</span>
        </div>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-dark mb-8 leading-tight">
          <span class="block">DiverMind</span>
          <span class="text-primary font-light text-3xl sm:text-4xl lg:text-5xl block mt-2">
            Conectando Mundos para el Éxito Integral
          </span>
        </h1>
        <p class="text-xl sm:text-2xl text-dark/70 max-w-4xl mx-auto font-light leading-relaxed">
          La primera plataforma que integra el mundo educativo con el terapéutico, 
          creando un ecosistema completo de apoyo para estudiantes con NEE
        </p>
      </div>

      <!-- Main Feature Section -->
      <div class="grid lg:grid-cols-2 gap-16 items-center mb-20">
        <!-- Left Column - Image -->
        <div class="relative">
          <div class="neo-image-container">
            <img 
              src="/img/team.png" 
              alt="Equipo colaborativo DiverMind" 
              class="w-full h-auto rounded-3xl"
            />
            <div class="neo-image-overlay">
              <div class="neo-floating-badge">
                <UserGroupIcon class="w-6 h-6 text-accent mr-2" />
                <span class="text-dark font-medium">Trabajo Colaborativo</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column - Main Content -->
        <div class="space-y-8">
          <div class="neo-content-card">
            <div class="neo-icon-highlight mb-6">
              <LinkIcon class="w-12 h-12 text-primary" />
            </div>
            <h2 class="text-3xl font-black text-dark mb-6">
              Triangulación Inteligente de Información
            </h2>
            <p class="text-dark/80 text-lg leading-relaxed mb-6">
              DiverMind revoluciona el apoyo a estudiantes con NEE al crear un puente único 
              entre <strong class="text-primary">tres pilares fundamentales</strong>: el equipo PIE educativo, 
              los apoderados comprometidos y los profesionales terapéuticos privados.
            </p>
            <div class="neo-highlight-box">
              <CheckCircleIcon class="w-6 h-6 text-accent mr-3 flex-shrink-0" />
              <p class="text-dark/70 font-medium">
                Primera plataforma en Chile que unifica la visión educativa y terapéutica 
                en un solo ecosistema digital
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Three Pillars Section -->
      <div class="mb-20">
        <h2 class="text-3xl font-black text-dark text-center mb-12">
          Los Tres Pilares del Éxito Integral
        </h2>
        <div class="grid md:grid-cols-3 gap-8">
          <!-- Pilar 1: Equipo PIE -->
          <div class="neo-pillar-card group">
            <div class="neo-pillar-icon bg-primary/10">
              <AcademicCapIcon class="w-10 h-10 text-primary" />
            </div>
            <h3 class="text-xl font-bold text-dark mb-4">Equipo PIE Educativo</h3>
            <p class="text-dark/70 leading-relaxed mb-4">
              Profesionales especializados en el desarrollo académico y adaptaciones 
              curriculares, enfocados en el desempeño educativo y logro de objetivos pedagógicos.
            </p>
            <div class="neo-pillar-features">
              <div class="feature-item">
                <div class="feature-dot bg-primary"></div>
                <span>Seguimiento académico especializado</span>
              </div>
              <div class="feature-item">
                <div class="feature-dot bg-primary"></div>
                <span>Adaptaciones curriculares personalizadas</span>
              </div>
            </div>
          </div>

          <!-- Pilar 2: Apoderados -->
          <div class="neo-pillar-card group">
            <div class="neo-pillar-icon bg-accent/10">
              <HeartIcon class="w-10 h-10 text-accent" />
            </div>
            <h3 class="text-xl font-bold text-dark mb-4">Apoderados Empoderados</h3>
            <p class="text-dark/70 leading-relaxed mb-4">
              Padres y cuidadores que aportan información valiosa sobre avances, retrocesos 
              y comportamientos en el hogar, creando una visión integral del estudiante.
            </p>
            <div class="neo-pillar-features">
              <div class="feature-item">
                <div class="feature-dot bg-accent"></div>
                <span>Registro de avances y retrocesos</span>
              </div>
              <div class="feature-item">
                <div class="feature-dot bg-accent"></div>
                <span>Comunicación directa con profesionales</span>
              </div>
            </div>
          </div>

          <!-- Pilar 3: Terapeutas Privados -->
          <div class="neo-pillar-card group">
            <div class="neo-pillar-icon bg-primary-light/10">
              <UserIcon class="w-10 h-10 text-primary-light" />
            </div>
            <h3 class="text-xl font-bold text-dark mb-4">Terapeutas Especializados</h3>
            <p class="text-dark/70 leading-relaxed mb-4">
              Psicólogos y terapeutas privados que los padres contratan, ahora con acceso 
              al contexto educativo completo para intervenciones más efectivas.
            </p>
            <div class="neo-pillar-features">
              <div class="feature-item">
                <div class="feature-dot bg-primary-light"></div>
                <span>Acceso a contexto educativo completo</span>
              </div>
              <div class="feature-item">
                <div class="feature-dot bg-primary-light"></div>
                <span>Intervenciones terapéuticas informadas</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Success Story Section -->
      <div class="mb-20">
        <div class="neo-story-card">
          <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
              <div class="neo-story-icon mb-6">
                <PuzzlePieceIcon class="w-12 h-12 text-accent" />
              </div>
              <h2 class="text-3xl font-black text-dark mb-6">
                Caso de Éxito: Estudiante con Autismo
              </h2>
              <p class="text-dark/80 text-lg leading-relaxed mb-6">
                Imagina a un niño con autismo que recibe apoyo del equipo PIE en su colegio, 
                pero sus padres también invierten en un terapeuta especializado. Antes, 
                estos profesionales trabajaban en silos separados.
              </p>
              <div class="space-y-4">
                <div class="success-step">
                  <div class="step-number">1</div>
                  <div>
                    <h4 class="font-bold text-dark mb-1">Contexto Unificado</h4>
                    <p class="text-dark/70 text-sm">El terapeuta privado accede al historial educativo completo</p>
                  </div>
                </div>
                <div class="success-step">
                  <div class="step-number">2</div>
                  <div>
                    <h4 class="font-bold text-dark mb-1">Estrategias Coordinadas</h4>
                    <p class="text-dark/70 text-sm">Ambos equipos alinean sus enfoques terapéuticos y educativos</p>
                  </div>
                </div>
                <div class="success-step">
                  <div class="step-number">3</div>
                  <div>
                    <h4 class="font-bold text-dark mb-1">Resultados Exponenciales</h4>
                    <p class="text-dark/70 text-sm">El estudiante recibe apoyo integral y consistente</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="space-y-6">
              <div class="neo-benefit-highlight">
                <div class="benefit-icon">
                  <ChartBarIcon class="w-8 h-8 text-primary" />
                </div>
                <div>
                  <h4 class="font-bold text-dark mb-2">Mejora Medible</h4>
                  <p class="text-dark/70">Hasta 3x más efectividad en intervenciones coordinadas</p>
                </div>
              </div>
              <div class="neo-benefit-highlight">
                <div class="benefit-icon">
                  <ClockIcon class="w-8 h-8 text-accent" />
                </div>
                <div>
                  <h4 class="font-bold text-dark mb-2">Ahorro de Tiempo</h4>
                  <p class="text-dark/70">Reduce reuniones y comunicaciones redundantes</p>
                </div>
              </div>
              <div class="neo-benefit-highlight">
                <div class="benefit-icon">
                  <StarIcon class="w-8 h-8 text-primary-light" />
                </div>
                <div>
                  <h4 class="font-bold text-dark mb-2">Satisfacción Familiar</h4>
                  <p class="text-dark/70">Padres más tranquilos con visión integral</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Innovation Section -->
      <div class="mb-20">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-black text-dark mb-6">
            Innovación que Marca la Diferencia
          </h2>
          <p class="text-xl text-dark/70 max-w-3xl mx-auto">
            DiverMind no es solo un software, es una revolución en el cuidado integral 
            de estudiantes con necesidades educativas especiales
          </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div 
            v-for="(innovation, index) in innovations" 
            :key="index"
            class="neo-innovation-card"
          >
            <div class="neo-innovation-icon">
              <component :is="innovation.icon" class="w-8 h-8 text-primary" />
            </div>
            <h3 class="font-bold text-dark mb-2">{{ innovation.title }}</h3>
            <p class="text-dark/60 text-sm">{{ innovation.description }}</p>
          </div>
        </div>
      </div>

      <!-- CTA Section -->
      <div class="text-center">
        <div class="neo-cta-card">
          <h2 class="text-3xl font-black text-dark mb-6">
            ¿Listo para Unir los Mundos Educativo y Terapéutico?
          </h2>
          <p class="text-dark/70 mb-8 max-w-2xl mx-auto leading-relaxed">
            Descubre cómo DiverMind puede transformar el apoyo integral que reciben 
            tus estudiantes con necesidades educativas especiales.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button class="neo-cta-button primary group">
              <span class="relative z-10">Solicitar Demo Gratuita</span>
              <PlayIcon class="w-5 h-5 ml-2 group-hover:scale-110 transition-transform duration-200" />
            </button>
            <button class="neo-cta-button secondary group">
              <span class="relative z-10">Conocer Más</span>
              <ArrowRightIcon class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-200" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { 
  UserGroupIcon,
  LinkIcon,
  CheckCircleIcon,
  AcademicCapIcon,
  HeartIcon,
  UserIcon,
  PuzzlePieceIcon,
  //TrendingUpIcon,
   ChartBarIcon,

  ClockIcon,
  StarIcon,
  PlayIcon,
  ArrowRightIcon,
  CogIcon,
  ShieldCheckIcon,
  LightBulbIcon,
  ChartBarSquareIcon
} from '@heroicons/vue/24/outline'

// Datos de innovaciones
const innovations = [
  {
    icon: CogIcon,
    title: "Integración Fluida",
    description: "Conecta sistemas educativos y terapéuticos sin fricción"
  },
  {
    icon: ShieldCheckIcon,
    title: "Privacidad Garantizada",
    description: "Protección de datos con estándares internacionales"
  },
  {
    icon: LightBulbIcon,
    title: "IA Predictiva",
    description: "Algoritmos que anticipan necesidades de apoyo"
  },
  {
    icon: ChartBarSquareIcon,
    title: "Analytics Avanzados",
    description: "Insights profundos para mejores decisiones"
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

.neo-image-container {
  @apply relative rounded-3xl overflow-hidden;
  box-shadow: 
    20px 20px 40px rgba(22, 66, 91, 0.15),
    -20px -20px 40px rgba(255, 255, 255, 0.7);
}

.neo-image-overlay {
  @apply absolute inset-0 bg-gradient-to-t from-dark/20 to-transparent flex items-end justify-center pb-8;
}

.neo-floating-badge {
  @apply flex items-center px-4 py-2 rounded-full bg-background/90 backdrop-blur-sm;
  box-shadow: 
    8px 8px 16px rgba(22, 66, 91, 0.2),
    -8px -8px 16px rgba(255, 255, 255, 0.8);
}

.neo-content-card {
  @apply p-8 rounded-3xl;
  background: #D9DCD6;
  box-shadow: 
    16px 16px 32px rgba(22, 66, 91, 0.15),
    -16px -16px 32px rgba(255, 255, 255, 0.7);
}

.neo-icon-highlight {
  @apply inline-flex p-4 rounded-2xl;
  background: #D9DCD6;
  box-shadow: 
    inset 8px 8px 16px rgba(22, 66, 91, 0.1),
    inset -8px -8px 16px rgba(255, 255, 255, 0.8);
}

.neo-highlight-box {
  @apply flex items-start p-4 rounded-2xl bg-accent/5;
  box-shadow: 
    inset 4px 4px 8px rgba(129, 195, 215, 0.1),
    inset -4px -4px 8px rgba(255, 255, 255, 0.8);
}

.neo-pillar-card {
  @apply p-8 rounded-3xl transition-all duration-300 cursor-pointer;
  background: #D9DCD6;
  box-shadow: 
    12px 12px 24px rgba(22, 66, 91, 0.15),
    -12px -12px 24px rgba(255, 255, 255, 0.7);
}

.neo-pillar-card:hover {
  transform: translateY(-4px);
  box-shadow: 
    16px 16px 32px rgba(22, 66, 91, 0.2),
    -16px -16px 32px rgba(255, 255, 255, 0.8);
}

.neo-pillar-icon {
  @apply w-16 h-16 rounded-2xl flex items-center justify-center mb-6;
  box-shadow: 
    inset 6px 6px 12px rgba(22, 66, 91, 0.1),
    inset -6px -6px 12px rgba(255, 255, 255, 0.8);
}

.neo-pillar-features {
  @apply space-y-3;
}

.feature-item {
  @apply flex items-center text-sm text-dark/70;
}

.feature-dot {
  @apply w-2 h-2 rounded-full mr-3 flex-shrink-0;
}

.neo-story-card {
  @apply p-12 rounded-3xl;
  background: #D9DCD6;
  box-shadow: 
    20px 20px 40px rgba(22, 66, 91, 0.15),
    -20px -20px 40px rgba(255, 255, 255, 0.7);
}

.neo-story-icon {
  @apply inline-flex p-4 rounded-2xl;
  background: #D9DCD6;
  box-shadow: 
    inset 8px 8px 16px rgba(22, 66, 91, 0.1),
    inset -8px -8px 16px rgba(255, 255, 255, 0.8);
}

.success-step {
  @apply flex items-start space-x-4;
}

.step-number {
  @apply w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold flex-shrink-0;
}

.neo-benefit-highlight {
  @apply flex items-start space-x-4 p-4 rounded-2xl;
  background: #D9DCD6;
  box-shadow: 
    8px 8px 16px rgba(22, 66, 91, 0.1),
    -8px -8px 16px rgba(255, 255, 255, 0.6);
}

.benefit-icon {
  @apply p-2 rounded-xl;
  background: #D9DCD6;
  box-shadow: 
    inset 4px 4px 8px rgba(22, 66, 91, 0.1),
    inset -4px -4px 8px rgba(255, 255, 255, 0.8);
}

.neo-innovation-card {
  @apply p-6 rounded-2xl text-center transition-all duration-300 cursor-pointer;
  background: #D9DCD6;
  box-shadow: 
    8px 8px 16px rgba(22, 66, 91, 0.1),
    -8px -8px 16px rgba(255, 255, 255, 0.6);
}

.neo-innovation-card:hover {
  transform: translateY(-2px);
  box-shadow: 
    12px 12px 24px rgba(22, 66, 91, 0.15),
    -12px -12px 24px rgba(255, 255, 255, 0.7);
}

.neo-innovation-icon {
  @apply w-12 h-12 rounded-xl mx-auto mb-4 flex items-center justify-center;
  background: #D9DCD6;
  box-shadow: 
    inset 4px 4px 8px rgba(22, 66, 91, 0.1),
    inset -4px -4px 8px rgba(255, 255, 255, 0.8);
}

.neo-cta-card {
  @apply p-12 rounded-3xl;
  background: #D9DCD6;
  box-shadow: 
    20px 20px 40px rgba(22, 66, 91, 0.15),
    -20px -20px 40px rgba(255, 255, 255, 0.7);
}

.neo-cta-button {
  @apply inline-flex items-center px-8 py-4 rounded-2xl font-medium transition-all duration-300;
}

.neo-cta-button.primary {
  @apply text-white;
  background: linear-gradient(135deg, #2F6690, #3A7CA5);
  box-shadow: 
    8px 8px 16px rgba(22, 66, 91, 0.3),
    -2px -2px 8px rgba(255, 255, 255, 0.2);
}

.neo-cta-button.secondary {
  @apply text-dark;
  background: #D9DCD6;
  box-shadow: 
    8px 8px 16px rgba(22, 66, 91, 0.15),
    -8px -8px 16px rgba(255, 255, 255, 0.7);
}

.neo-cta-button:hover {
  transform: translateY(-2px);
}

.neo-cta-button.primary:hover {
  box-shadow: 
    12px 12px 24px rgba(22, 66, 91, 0.4),
    -4px -4px 12px rgba(255, 255, 255, 0.3);
}

.neo-cta-button.secondary:hover {
  box-shadow: 
    12px 12px 24px rgba(22, 66, 91, 0.2),
    -12px -12px 24px rgba(255, 255, 255, 0.8);
}

/* Responsive */
@media (max-width: 768px) {
  .neo-content-card,
  .neo-story-card,
  .neo-cta-card {
    @apply p-6;
  }
  
  .neo-pillar-card {
    @apply p-6;
  }
}

/* Animaciones */
@keyframes pulse-glow {
  0%, 100% { 
    box-shadow: 
      8px 8px 16px rgba(22, 66, 91, 0.3),
      -2px -2px 8px rgba(255, 255, 255, 0.2);
  }
  50% { 
    box-shadow: 
      12px 12px 24px rgba(22, 66, 91, 0.4),
      -4px -4px 12px rgba(255, 255, 255, 0.3);
  }
}

.neo-cta-button.primary {
  animation: pulse-glow 3s ease-in-out infinite;
}

/* Accesibilidad */
@media (prefers-reduced-motion: reduce) {
  .neo-cta-button.primary {
    animation: none;
  }
  
  .neo-pillar-card:hover,
  .neo-innovation-card:hover,
  .neo-cta-button:hover {
    transform: none;
  }
}

.neo-cta-button:focus-visible,
.neo-pillar-card:focus-visible,
.neo-innovation-card:focus-visible {
  outline: 2px solid #3A7CA5;
  outline-offset: 2px;
}
</style>
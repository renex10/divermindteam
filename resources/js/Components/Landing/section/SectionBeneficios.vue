<template>
  <section class="py-20 px-4 sm:px-6 lg:px-8 bg-background">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-16">
        <div class="neo-badge mb-6">
          <span class="text-primary font-medium">Para las Familias</span>
        </div>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-dark mb-6">
          <span class="block">Seguimiento Integral</span>
          <span class="text-primary font-light text-2xl sm:text-3xl lg:text-4xl block mt-2">
            Conectando cada avance con la familia
          </span>
        </h2>
        <p class="text-xl text-dark/70 max-w-3xl mx-auto">
          DiverMind brinda visibilidad completa del desarrollo de su niño, integrando todas las áreas de crecimiento
        </p>
      </div>

      <div class="grid lg:grid-cols-2 gap-16 items-center">
        <!-- Columna izquierda: Imagen -->
        <div class="relative">
          <div class="neo-image-container">
            <img 
              src="/img/estadistica.png" 
              alt="Seguimiento de avances en DiverMind" 
              class="w-full h-auto rounded-3xl"
            />
            <div class="neo-image-overlay">
              <div class="neo-floating-badge">
                <LightBulbIcon class="w-6 h-6 text-accent mr-2" />
                <span class="text-dark font-medium">Decisiones Informadas</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Columna derecha: Beneficios -->
        <div class="space-y-8">
          <div class="neo-content-card">
            <div class="flex items-center mb-6">
              <div class="neo-icon-highlight mr-4">
                <ChartBarIcon class="w-8 h-8 text-primary" />
              </div>
              <h3 class="text-2xl font-black text-dark">
                Visión Clara del Desarrollo
              </h3>
            </div>
            <p class="text-dark/80 text-lg leading-relaxed mb-6">
              Las familias pueden observar <strong class="text-primary">avances, estancamientos y retrocesos</strong> 
              en todas las áreas clave del desarrollo infantil:
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
              <div class="neo-highlight-box">
                <UserGroupIcon class="w-6 h-6 text-accent mr-3 flex-shrink-0" />
                <p class="text-dark/70">Habilidades sociales</p>
              </div>
              <div class="neo-highlight-box">
                <AcademicCapIcon class="w-6 h-6 text-accent mr-3 flex-shrink-0" />
                <p class="text-dark/70">Habilidades cognitivas</p>
              </div>
              <div class="neo-highlight-box">
                <ChatBubbleBottomCenterTextIcon class="w-6 h-6 text-accent mr-3 flex-shrink-0" />
                <p class="text-dark/70">Comunicación verbal</p>
              </div>
              <div class="neo-highlight-box">
                <HeartIcon class="w-6 h-6 text-accent mr-3 flex-shrink-0" />
                <p class="text-dark/70">Integración social</p>
              </div>
            </div>
            
            <p class="text-dark/80 text-lg leading-relaxed">
              Todo basado en los <strong class="text-primary">hitos del desarrollo</strong> y evaluado por profesionales
            </p>
          </div>

          <div class="grid md:grid-cols-2 gap-6">
            <div class="neo-pillar-card">
              <div class="flex items-start">
                <div class="neo-pillar-icon bg-primary/10 mr-4">
                  <ClipboardDocumentCheckIcon class="w-8 h-8 text-primary" />
                </div>
                <div>
                  <h3 class="text-xl font-bold text-dark mb-2">Evaluaciones Constantes</h3>
                  <p class="text-dark/70 text-sm">
                    Terapeutas y psicólogos suben evaluaciones periódicas con análisis profesionales
                  </p>
                </div>
              </div>
            </div>
            
            <div class="neo-pillar-card">
              <div class="flex items-start">
                <div class="neo-pillar-icon bg-accent/10 mr-4">
                  <CalendarIcon class="w-8 h-8 text-accent" />
                </div>
                <div>
                  <h3 class="text-xl font-bold text-dark mb-2">Planificación Integrada</h3>
                  <p class="text-dark/70 text-sm">
                    Profesores y terapeutas crean planes coordinados para cada niño
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Puente entre profesionales -->
      <div class="neo-story-card mt-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div>
            <div class="neo-story-icon mb-6">
              <LinkIcon class="w-12 h-12 text-accent" />
            </div>
            <h2 class="text-3xl font-black text-dark mb-6">
              El Puente que Conecta Mundos
            </h2>
            <p class="text-dark/80 text-lg leading-relaxed mb-6">
              DiverMind elimina las barreras entre el <strong class="text-primary">equipo PIE del colegio</strong> 
              y los <strong class="text-primary-light">terapeutas privados</strong>, creando un ecosistema único:
            </p>
            
            <div class="space-y-4 mb-6">
              <div class="success-step">
                <div class="step-number">
                  <ChatBubbleLeftEllipsisIcon class="w-5 h-5" />
                </div>
                <div>
                  <h4 class="font-bold text-dark mb-1">Comunicación Directa</h4>
                  <p class="text-dark/70 text-sm">Sección de preguntas y respuestas sobre el niño</p>
                </div>
              </div>
              <div class="success-step">
                <div class="step-number">
                  <BellIcon class="w-5 h-5" />
                </div>
                <div>
                  <h4 class="font-bold text-dark mb-1">Notificaciones Relevantes</h4>
                  <p class="text-dark/70 text-sm">Actualizaciones sobre avances y nuevas evaluaciones</p>
                </div>
              </div>
              <div class="success-step">
                <div class="step-number">
                  <DocumentTextIcon class="w-5 h-5" />
                </div>
                <div>
                  <h4 class="font-bold text-dark mb-1">Documentación Centralizada</h4>
                  <p class="text-dark/70 text-sm">Toda la información en un solo lugar accesible</p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="space-y-6">
            <div class="neo-benefit-highlight">
              <div class="benefit-icon">
                <EyeIcon class="w-8 h-8 text-primary" />
              </div>
              <div>
                <h4 class="font-bold text-dark mb-2">Transparencia Total</h4>
                <p class="text-dark/70">Las familias ven exactamente cómo progresa su niño</p>
              </div>
            </div>
            
            <div class="neo-benefit-highlight">
              <div class="benefit-icon">
                <ArrowPathIcon class="w-8 h-8 text-accent" />
              </div>
              <div>
                <h4 class="font-bold text-dark mb-2">Retroalimentación Constante</h4>
                <p class="text-dark/70">Actualizaciones periódicas de todos los profesionales</p>
              </div>
            </div>
            
            <div class="neo-benefit-highlight">
              <div class="benefit-icon">
                <UserGroupIcon class="w-8 h-8 text-primary-light" />
              </div>
              <div>
                <h4 class="font-bold text-dark mb-2">Equipo Unificado</h4>
                <p class="text-dark/70">Todos trabajando juntos por el mismo objetivo</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
// Importaciones correctas para Vue 3 con Heroicons
import { 
  LightBulbIcon,
  ChartBarIcon,
  UserGroupIcon,
  AcademicCapIcon,
  HeartIcon,
  LinkIcon,
  ClipboardDocumentCheckIcon,
  CalendarIcon,
  ChatBubbleLeftEllipsisIcon,
  BellIcon,
  DocumentTextIcon,
  EyeIcon,
  ArrowPathIcon,
  ChatBubbleBottomCenterTextIcon
} from '@heroicons/vue/24/outline'
</script>

<style scoped>
/* Componentes neomórficos reutilizables */
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
  @apply inline-flex p-3 rounded-2xl;
  background: #D9DCD6;
  box-shadow: 
    inset 6px 6px 12px rgba(22, 66, 91, 0.1),
    inset -6px -6px 12px rgba(255, 255, 255, 0.8);
}

.neo-highlight-box {
  @apply flex items-start p-4 rounded-2xl;
  background: #D9DCD6;
  box-shadow: 
    inset 4px 4px 8px rgba(22, 66, 91, 0.1),
    inset -4px -4px 8px rgba(255, 255, 255, 0.8);
}

.neo-pillar-card {
  @apply p-6 rounded-3xl h-full;
  background: #D9DCD6;
  box-shadow: 
    8px 8px 16px rgba(22, 66, 91, 0.15),
    -8px -8px 16px rgba(255, 255, 255, 0.7);
}

.neo-pillar-icon {
  @apply w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0;
  box-shadow: 
    inset 4px 4px 8px rgba(22, 66, 91, 0.1),
    inset -4px -4px 8px rgba(255, 255, 255, 0.8);
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
  @apply p-2 rounded-xl flex-shrink-0;
  background: #D9DCD6;
  box-shadow: 
    inset 4px 4px 8px rgba(22, 66, 91, 0.1),
    inset -4px -4px 8px rgba(255, 255, 255, 0.8);
}

/* Responsive */
@media (max-width: 768px) {
  .neo-content-card,
  .neo-story-card {
    @apply p-6;
  }
}
</style>
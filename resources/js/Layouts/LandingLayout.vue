<template>
  <div class="min-h-screen flex flex-col">
    <!-- 1. Tu barra de navegación -->
    <Navigation />

    <!-- 2. Contenido de cada página -->
    <main class="flex-1">
      <slot />
    </main>
  </div>
</template>

<script setup>
// Importa tu componente
import Navigation from '@/Components/Landing/navigation/Navigation.vue'
</script>
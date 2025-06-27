<script setup>
// 1. Importa tu layout
import LandingLayout from '@/Layouts/LandingLayout.vue'
// 2. Importa el componente QueEsFeature
import QueEsFeature from '@/Components/Landing/feature/QueEsFeature.vue'

// 3. Asigna el layout
defineOptions({
  layout: LandingLayout
})
</script>

<template>
  <div>
    <!-- Componente principal que describe qué es el software -->
    <QueEsFeature />
  </div>
</template>
<script setup>
// 1. Importa tu layout
import LandingLayout from '@/Layouts/LandingLayout.vue'
// 2. Importa el componente FeatureSection
import FeatureSection from '@/Components/Landing/feature/FeatureSection.vue';

// 3. Asigna el layout
defineOptions({
  layout: LandingLayout
})
</script>

<template>
  <div>
    <!-- Componente principal de características avanzadas -->
    <FeatureSection />
  </div>
</template>
<script setup>
import LandingLayout from '@/Layouts/LandingLayout.vue'
import SectionBeneficios from '@/Components/Landing/section/SectionBeneficios.vue'

defineOptions({
  layout: LandingLayout
})
</script>

<template>
  <div>
    <!-- Componente de beneficios para familias -->
    <SectionBeneficios />
  </div>
</template>
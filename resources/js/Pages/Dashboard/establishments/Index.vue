<template>
  <DashboardLayout title="Gestión de Establecimientos">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Establecimiento</h2>
    <p class="text-sm text-gray-500 mb-6">Aquí irá el formulario o lista de establecimientos</p>

    <!-- Componente especializado con datos pasados como props -->
    <EstablishmentTable :establecimientos="establishments" />
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import EstablishmentTable from '@/Components/Table/Establishment.vue'

// Recibir los datos del controlador
const props = defineProps({
  establishments: {
    type: Array,
    default: () => []
  }
})
</script>
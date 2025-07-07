<template>
  <DashboardLayout>
    <!-- Título en el slot "header" del layout -->
    <template #header>
      <h1 class="text-2xl font-bold">Especialidades</h1>
    </template>

    <!-- Aquí insertas la tabla reutilizable -->
    <SpecialtiesTable :specialties="specialties" />
  </DashboardLayout>
</template>

<script>
export default {
    name: 'SpecialtyIndex'
}
</script>

<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import SpecialtiesTable from '@/Components/Table/Specialties.vue'

// Props corregidas
defineProps ({
    specialties: {  // ✅ Sin el $ y coincide con el controlador
        type: Object,
        required: true
    }
})
</script>
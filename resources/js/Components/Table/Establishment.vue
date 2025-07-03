<template>
  <div>
    <!-- 1. Acciones de la tabla (buscador y filtro por región) -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
      <!-- Búsqueda por nombre -->
      <input
        v-model="busqueda"
        type="text"
        placeholder="Buscar por nombre..."
        class="border border-gray-300 rounded px-4 py-2 w-full md:w-1/3"
      />

      <!-- Filtro por región -->
      <select
        v-model="regionSeleccionada"
        class="border border-gray-300 rounded px-4 py-2 w-full md:w-1/3"
      >
        <option value="">Todas las regiones</option>
        <option v-for="region in regionesUnicas" :key="region" :value="region">
          {{ region }}
        </option>
      </select>
    </div>

    <!-- 2. Tabla base reutilizable con datos filtrados -->
    <TablaBase
      titulo="Lista de Establecimientos"
      :columnas="columnas"
      :datos="establecimientosFiltrados"
      :cargando="false"
      texto-vacio="No hay establecimientos que coincidan"
      texto-cargando="Cargando..."
    />
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import TablaBase from '@/Components/TablaBase/TablaBase.vue'

/**
 * 3. Definir las props que recibe el componente
 */
const props = defineProps({
  establishments: {
    type: Array,
    default: () => []
  }
})

/**
 * 4. Usar las props recibidas en lugar de page.props
 */
const establecimientos = computed(() => props.establishments || [])

/**
 * 5. Columnas visibles en la tabla
 */
const columnas = [
  { titulo: 'Nombre', campo: 'name', sortable: true },
  { titulo: 'Dirección', campo: 'address' },
  { titulo: 'Región', campo: 'commune.region.name' },
  { titulo: 'Comuna', campo: 'commune.name' },
  { titulo: 'Cupo PIE', campo: 'pie_quota_max' }
]

/**
 * 6. Filtros reactivos
 */
const busqueda = ref('')
const regionSeleccionada = ref('')

/**
 * 7. Filtro computado basado en los campos seleccionados
 */
const establecimientosFiltrados = computed(() => {
  if (!Array.isArray(establecimientos.value)) {
    console.warn('establecimientos no es un array:', establecimientos.value)
    return []
  }

  return establecimientos.value.filter(est => {
    const coincideNombre = est.name?.toLowerCase().includes(busqueda.value.toLowerCase())
    const coincideRegion = regionSeleccionada.value
      ? est.commune?.region?.name === regionSeleccionada.value
      : true

    return coincideNombre && coincideRegion
  })
})

/**
 * 8. Lista de regiones únicas para el filtro
 */
const regionesUnicas = computed(() => {
  if (!Array.isArray(establecimientos.value)) {
    return []
  }

  const regiones = establecimientos.value
    .map(est => est.commune?.region?.name)
    .filter(Boolean)

  return [...new Set(regiones)].sort()
})
</script>
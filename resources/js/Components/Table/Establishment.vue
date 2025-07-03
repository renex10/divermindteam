<template>
  <!-- Contenedor principal del componente -->
  <div>
    <!-- 1. SECCIÓN DE CONTROLES SUPERIORES -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
      <!-- Campo de búsqueda por nombre -->
      <input
        v-model="busqueda"
        type="text"
        placeholder="Buscar por nombre..."
        class="border border-gray-300 rounded px-4 py-2 w-full md:w-1/3"
      />

      <!-- Contenedor para botón y filtro -->
      <div class="flex items-center space-x-4 w-full md:w-auto">
        <!-- Botón para abrir modal de nuevo establecimiento -->
        <ButtonAdd 
          @openModal="$emit('openModal')"
          label="Agregar establecimiento"
          class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:border-green-800 focus:ring-green-300"
        />

        <!-- Selector de región -->
        <select
          v-model="regionSeleccionada"
          class="border border-gray-300 rounded px-4 py-2 w-full md:w-48"
        >
          <option value="">Todas las regiones</option>
          <option v-for="region in regionesUnicas" :key="region" :value="region">
            {{ region }}
          </option>
        </select>
      </div>
    </div>

    <!-- 2. COMPONENTE TABLA PRINCIPAL -->
    <TablaBase
      titulo="Lista de Establecimientos"
      :columnas="columnas"
      :datos="establecimientosFiltrados"
      :cargando="false"
      texto-vacio="No hay establecimientos que coincidan"
      texto-cargando="Cargando..."
    >
      <!-- Slot para columna Nombre -->
      <template #nombre="{ fila }">
        <div class="flex items-center">
          <span class="font-mono bg-gray-100 rounded px-2 py-1 mr-2">
            #{{ fila.id }}
          </span>
          <span>{{ fila.name }}</span>
        </div>
      </template>

      <!-- Slot para columna Estado -->
      <template #estado="{ fila }">
        <ToggleBase 
          :model-value="Boolean(fila.is_active)" 
          @update:modelValue="nuevoEstado => toggleEstado(fila.id, nuevoEstado)"
          :active-label="fila.is_active ? 'Activo' : 'Inactivo'"
          class="ml-2"
        />
      </template>

      <!-- Slot para columna Acciones: Solo Editar -->
      <template #acciones="{ fila }">
        <div class="flex items-center" v-if="fila">
          <!-- Ícono para editar establecimiento -->
          <button @click="editarEstablecimiento(fila.id)" class="text-blue-600 hover:text-blue-800">
            <PencilSquareIcon class="w-5 h-5" />
          </button>
        </div>
      </template>
    </TablaBase>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import TablaBase from '@/Components/TablaBase/TablaBase.vue'
import ButtonAdd from '@/Components/Bottom/ButtonAdd.vue'
import ToggleBase from '@/Components/Toggle/ToggleBase.vue'
import { Inertia } from '@inertiajs/inertia'
import { PencilSquareIcon } from '@heroicons/vue/24/outline'

const emit = defineEmits(['openModal'])

const props = defineProps({
  establishments: {
    type: Array,
    default: () => []
  }
})

// Reactivo para la lista de establecimientos
const establecimientos = computed(() => props.establishments || [])

// Columnas configuradas para TablaBase
const columnas = [
  { titulo: 'Nombre', slot: 'nombre', sortable: true, campo: 'name' },
  { titulo: 'Estado', slot: 'estado', sortable: true, campo: 'is_active' },
  { titulo: 'Dirección', campo: 'address' },
  { titulo: 'Cupo PIE', campo: 'pie_quota_max' },
  { titulo: 'Comuna', campo: 'commune.name' },
  { titulo: 'Región', campo: 'commune.region.name' },
  { titulo: 'Acciones', slot: 'acciones', sortable: false }
]

// Filtros reactivos
const busqueda = ref('')
const regionSeleccionada = ref('')

// Filtra establecimientos por búsqueda y región
const establecimientosFiltrados = computed(() => {
  if (!Array.isArray(establecimientos.value)) return []

  return establecimientos.value.filter(est => {
    const coincideNombre = est.name?.toLowerCase().includes(busqueda.value.toLowerCase())
    const coincideRegion = regionSeleccionada.value
      ? est.commune?.region?.name === regionSeleccionada.value
      : true
    return coincideNombre && coincideRegion
  })
})

// Regiones únicas para filtro
const regionesUnicas = computed(() => {
  if (!Array.isArray(establecimientos.value)) return []
  const regiones = establecimientos.value
    .map(est => est.commune?.region?.name)
    .filter(Boolean)
  return [...new Set(regiones)].sort()
})

// Navega a página edición
function editarEstablecimiento(id) {
  Inertia.visit(route('establishments.edit', id))
}

// Actualiza estado activo/inactivo (booleano)
function toggleEstado(id, nuevoEstado) {
  // Debug console logs para seguimiento
  console.log('=== INICIO TOGGLE DEBUG ===')
  console.log('ID:', id)
  console.log('Nuevo estado:', nuevoEstado)

  const url = `/establishments/${id}`

  Inertia.put(url, {
    is_active: nuevoEstado
  }, {
    preserveScroll: true,
    onStart: () => console.log('🚀 Petición PUT a:', url),
    onSuccess: (page) => {
      console.log('✅ Estado actualizado con éxito')
      // Actualiza localmente el estado para reflejar cambio inmediato
      const index = establecimientos.value.findIndex(e => e.id === id)
      if (index !== -1) {
        establecimientos.value[index].is_active = nuevoEstado
      }
    },
    onError: (errors) => {
      console.error('❌ Error al actualizar estado:', errors)
      // Opcional: revertir toggle si falla
      const index = establecimientos.value.findIndex(e => e.id === id)
      if (index !== -1) {
        establecimientos.value[index].is_active = !nuevoEstado
      }
    },
    onFinish: () => console.log('🏁 Petición finalizada\n=== FIN TOGGLE DEBUG ===')
  })
}
</script>

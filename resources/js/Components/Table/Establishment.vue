// 🧾 COMPONENTE TABLA SIMPLIFICADO (para Establishment.vue)
<script setup>
import { computed, ref, watch } from 'vue'
import TablaBase from '@/Components/TablaBase/TablaBase.vue'
import ButtonAdd from '@/Components/Bottom/ButtonAdd.vue'
import ToggleBase from '@/Components/Toggle/ToggleBase.vue'
import { router } from '@inertiajs/vue3'
import { PencilSquareIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  establishments: {
    type: Array,
    default: () => []
  },
  busquedaExterna: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['openModal', 'establecimiento-actualizado'])

// 🔄 Usar directamente los props (ya vienen filtrados del padre)
const establecimientos = computed(() => props.establishments || [])

// Columnas configuradas para TablaBase
const columnas = [
  { titulo: 'Nombre', slot: 'nombre', sortable: true, campo: 'name' },
  { titulo: 'RBD', slot: 'rbd', sortable: true, campo: 'rbd' },
  { titulo: 'Estado', slot: 'estado', sortable: true, campo: 'is_active' },
  { titulo: 'Cupo PIE', campo: 'pie_quota_max' },
  { titulo: 'Comuna', campo: 'commune.name' },
  { titulo: 'Región', campo: 'commune.region.name' },
  { titulo: 'Acciones', slot: 'acciones', sortable: false }
]

// 🗑️ ELIMINADO: Filtrado local (ya se hace en el componente padre)
// 🗑️ ELIMINADO: Filtro por región (ya se hace en el componente padre)

// Navega a página edición
function editarEstablecimiento(id) {
  router.visit(route('establishments.edit', id))
}

// 🔄 Toggle de estado optimista
function toggleEstado(id, nuevoEstado) {
  console.log('=== TOGGLE ESTADO ===')
  console.log('ID:', id, 'Nuevo estado:', nuevoEstado)

  // 1. 🚀 Emitir cambio al padre inmediatamente
  emit('establecimiento-actualizado', id, { is_active: nuevoEstado })

  // 2. 🌐 Petición al servidor
  const url = `/establishments/${id}`
  
  router.put(url, {
    is_active: nuevoEstado
  }, {
    preserveScroll: true,
    preserveState: true, // 🎯 Mantener estado para evitar recargas
    only: [], // No necesitamos actualizar props
    onStart: () => {
      console.log('🚀 Actualizando estado en servidor...')
    },
    onSuccess: () => {
      console.log('✅ Estado actualizado exitosamente')
    },
    onError: (errors) => {
      console.error('❌ Error al actualizar estado:', errors)
      
      // 🔙 Revertir cambio en caso de error
      emit('establecimiento-actualizado', id, { is_active: !nuevoEstado })
      
      // Mostrar notificación de error
      alert('Error al actualizar el estado. Por favor, intenta nuevamente.')
    }
  })
}
</script>

<template>
  <!-- Contenedor principal del componente -->
  <div>
    <!-- 1. SECCIÓN DE CONTROLES SUPERIORES -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
      <!-- Información sobre búsqueda activa -->
      <div class="flex-1">
        <div v-if="busquedaExterna" class="text-sm text-gray-600 bg-blue-50 border border-blue-200 rounded-lg px-3 py-2">
          <span class="font-medium">Búsqueda activa:</span>
          <span class="text-blue-700">"{{ busquedaExterna }}"</span>
          <span class="text-gray-500 ml-2">
            ({{ establecimientos.length }} resultados)
          </span>
        </div>
        <div v-else class="text-sm text-gray-600">
          Mostrando {{ establecimientos.length }} establecimientos
        </div>
      </div>

      <!-- Botón para agregar establecimiento -->
      <div class="flex items-center space-x-4 w-full md:w-auto">
        <ButtonAdd 
          @openModal="$emit('openModal')"
          label="Agregar establecimiento"
          class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:border-green-800 focus:ring-green-300"
        />
      </div>
    </div>

    <!-- 2. COMPONENTE TABLA PRINCIPAL -->
    <TablaBase
      titulo="Lista de Establecimientos"
      :columnas="columnas"
      :datos="establecimientos"
      :cargando="false"
      texto-vacio="No hay establecimientos que coincidan con los filtros"
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

      <!-- Slot para columna RBD -->
      <template #rbd="{ fila }">
        <div class="flex items-center">
          <span class="font-mono bg-blue-100 text-blue-800 rounded px-2 py-1 font-semibold">
            {{ fila.rbd }}
          </span>
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

      <!-- Slot para columna Acciones -->
      <template #acciones="{ fila }">
        <div class="flex items-center" v-if="fila">
          <button @click="editarEstablecimiento(fila.id)" class="text-blue-600 hover:text-blue-800">
            <PencilSquareIcon class="w-5 h-5" />
          </button>
        </div>
      </template>
    </TablaBase>
  </div>
</template>
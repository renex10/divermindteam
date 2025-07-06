<script setup>
/* resources\js\Components\Table\Establishment.vue */
// 1. Importaciones necesarias
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import TablaBase from '@/Components/TablaBase/TablaBase.vue'
import ButtonAdd from '@/Components/Bottom/ButtonAdd.vue'
import ToggleBase from '@/Components/Toggle/ToggleBase.vue'
import { PencilSquareIcon } from '@heroicons/vue/24/outline'
import EditEstablishmentModal from '@/Components/Modal/EditEstablishmentModal.vue'

// 2. Definir props con valores por defecto
const props = defineProps({
  establishments: {
    type: Array,
    default: () => []
  },
  busquedaExterna: {
    type: String,
    default: ''
  },
  regiones: {
    type: Array,
    default: () => []
  },
  comunas: {
    type: Array,
    default: () => []
  }
})

// 3. Eventos emitidos - CORREGIDO
const emit = defineEmits([
  'openModal', 
  'establecimiento-actualizado',
  'establecimiento-editado'
])

// 4. Estados del componente
const editModalOpen = ref(false)
const selectedEstablishment = ref(null)

// 5. Computed properties
const establecimientos = computed(() => props.establishments || [])
const regionesSeguras = computed(() => props.regiones || [])
const comunasSeguras = computed(() => props.comunas || [])

// 6. Columnas de la tabla
const columnas = [
  { titulo: 'Nombre', slot: 'nombre', sortable: true, campo: 'name' },
  { titulo: 'RBD', slot: 'rbd', sortable: true, campo: 'rbd' },
  { titulo: 'Estado', slot: 'estado', sortable: true, campo: 'is_active' },
  { titulo: 'Cupo PIE', campo: 'pie_quota_max' },
  { titulo: 'Comuna', campo: 'commune.name' },
  { titulo: 'Región', campo: 'commune.region.name' },
  { titulo: 'Acciones', slot: 'acciones', sortable: false }
]

// 7. Función para abrir modal de edición
function openEditModal(establishment) {
  selectedEstablishment.value = establishment
  editModalOpen.value = true
}

// 8. CORREGIDO: Función para manejar actualización desde el modal
function handleEstablishmentUpdated(updatedData) {
  console.log('🔄 Establecimiento actualizado:', updatedData)
  
  // Emitir evento al componente padre con los datos actualizados
  emit('establecimiento-editado', selectedEstablishment.value.id, updatedData)
  
  // Cerrar modal
  editModalOpen.value = false
  selectedEstablishment.value = null
}

// 9. Función para cambiar estado (toggle)
function toggleEstado(id, nuevoEstado) {
  // Emitir cambio optimista al padre
  emit('establecimiento-actualizado', id, { is_active: nuevoEstado })

  // Petición al servidor
  router.put(route('establishments.update', id), {
    is_active: nuevoEstado
  }, {
    preserveScroll: true,
    preserveState: true,
    onError: (errors) => {
      console.error('Error al actualizar estado:', errors)
      // Revertir en caso de error
      emit('establecimiento-actualizado', id, { is_active: !nuevoEstado })
      alert('Error al actualizar el estado. Por favor, intenta nuevamente.')
    }
  })
}

// 10. Función para cerrar modal
function closeEditModal() {
  editModalOpen.value = false
  selectedEstablishment.value = null
}
</script>

<template>
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
          <button 
            @click="openEditModal(fila)" 
            class="text-blue-600 hover:text-blue-800 transition-colors"
            title="Editar establecimiento"
          >
            <PencilSquareIcon class="w-5 h-5" />
          </button>
        </div>
      </template>
    </TablaBase>

    <!-- 3. MODAL DE EDICIÓN -->
    <EditEstablishmentModal 
      v-if="selectedEstablishment"
      :isOpen="editModalOpen"
      :establishment="selectedEstablishment"
      :regiones="regionesSeguras"  
      :comunas="comunasSeguras"    
      @close="closeEditModal"
      @updated="handleEstablishmentUpdated"
    />
  </div>
</template>
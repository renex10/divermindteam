<template>
<!--   resources\js\Components\Table\Establishment.vue -->
  <!-- Contenedor principal del componente -->
  <div>
    <!-- 1. SECCIÓN DE CONTROLES SUPERIORES -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
      <!-- Campo de búsqueda por nombre y RBD -->
      <input
        v-model="busqueda"
        type="text"
        placeholder="Buscar por nombre o RBD..."
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
// CORRECCIÓN: Importar watch desde Vue
import { computed, ref, watch } from 'vue'
import TablaBase from '@/Components/TablaBase/TablaBase.vue'
import ButtonAdd from '@/Components/Bottom/ButtonAdd.vue'
import ToggleBase from '@/Components/Toggle/ToggleBase.vue'
import { router } from '@inertiajs/vue3'
import { PencilSquareIcon } from '@heroicons/vue/24/outline'

// 🆕 MODIFICACIÓN: Agregar nuevo evento para comunicar cambios al padre
const emit = defineEmits(['openModal', 'establecimiento-actualizado'])

const props = defineProps({
  establishments: {
    type: Array,
    default: () => []
  }
})

// 🔄 MODIFICACIÓN: Usar ref para manejar establecimientos localmente
const establecimientos = ref([...(props.establishments || [])])

// 👁️‍🗨️ Observar cambios en props para sincronizar (CORREGIDO: ahora watch está importado)
watch(() => props.establishments, (newEstablishments) => {
  establecimientos.value = [...(newEstablishments || [])]
}, { immediate: true })

// Columnas configuradas para TablaBase - CAMBIO: Dirección por RBD
const columnas = [
  { titulo: 'Nombre', slot: 'nombre', sortable: true, campo: 'name' },
  { titulo: 'RBD', slot: 'rbd', sortable: true, campo: 'rbd' },
  { titulo: 'Estado', slot: 'estado', sortable: true, campo: 'is_active' },
  { titulo: 'Cupo PIE', campo: 'pie_quota_max' },
  { titulo: 'Comuna', campo: 'commune.name' },
  { titulo: 'Región', campo: 'commune.region.name' },
  { titulo: 'Acciones', slot: 'acciones', sortable: false }
]

// Filtros reactivos
const busqueda = ref('')
const regionSeleccionada = ref('')

// Filtra establecimientos por búsqueda (nombre y RBD) y región
const establecimientosFiltrados = computed(() => {
  if (!Array.isArray(establecimientos.value)) return []

  return establecimientos.value.filter(est => {
    // Búsqueda por nombre O RBD
    const coincideNombre = est.name?.toLowerCase().includes(busqueda.value.toLowerCase())
    const coincideRBD = est.rbd?.toString().includes(busqueda.value)
    const coincideBusqueda = coincideNombre || coincideRBD
    
    // Filtro por región
    const coincideRegion = regionSeleccionada.value
      ? est.commune?.region?.name === regionSeleccionada.value
      : true
    
    return coincideBusqueda && coincideRegion
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
  router.visit(route('establishments.edit', id))
}

// 🔄 MODIFICACIÓN CRÍTICA: Actualización optimista del estado
function toggleEstado(id, nuevoEstado) {
  console.log('=== INICIO TOGGLE OPTIMISTA ===')
  console.log('ID:', id)
  console.log('Nuevo estado:', nuevoEstado)
  console.log('Estado actual de la lista:', establecimientos.value.find(e => e.id === id)?.is_active)

  // 1. 🚀 ACTUALIZACIÓN OPTIMISTA INMEDIATA
  const index = establecimientos.value.findIndex(e => e.id === id)
  if (index !== -1) {
    // Actualizar inmediatamente en la UI
    establecimientos.value[index].is_active = nuevoEstado
    console.log('✅ Estado actualizado optimistamente')
    
    // Emitir al padre para que también actualice su estado
    emit('establecimiento-actualizado', id, { is_active: nuevoEstado })
  }

  // 2. 🌐 LLAMADA AL SERVIDOR (con manejo de errores)
  const url = `/establishments/${id}`
  
  router.put(url, {
    is_active: nuevoEstado
  }, {
    preserveScroll: true,
    onStart: () => {
      console.log('🚀 Petición PUT iniciada a:', url)
    },
    onSuccess: (page) => {
      console.log('✅ Confirmación del servidor recibida')
      
      // 3. 🔄 SINCRONIZACIÓN OPCIONAL CON SERVIDOR
      // Solo si hay discrepancias, actualizar desde la respuesta
      if (page.props.updatedData) {
        const serverData = page.props.updatedData
        const localIndex = establecimientos.value.findIndex(e => e.id === serverData.id)
        if (localIndex !== -1 && establecimientos.value[localIndex].is_active !== serverData.is_active) {
          console.log('🔄 Sincronizando con datos del servidor')
          establecimientos.value[localIndex].is_active = serverData.is_active
        }
      }
    },
    onError: (errors) => {
      console.error('❌ Error al actualizar estado:', errors)
      
      // 4. 🔙 REVERTIR CAMBIO OPTIMISTA EN CASO DE ERROR
      const revertIndex = establecimientos.value.findIndex(e => e.id === id)
      if (revertIndex !== -1) {
        const estadoRevertido = !nuevoEstado
        establecimientos.value[revertIndex].is_active = estadoRevertido
        console.log('🔙 Estado revertido por error:', estadoRevertido)
        
        // Emitir reversión al padre
        emit('establecimiento-actualizado', id, { is_active: estadoRevertido })
      }
      
      // Mostrar notificación de error (opcional)
      alert('Error al actualizar el estado. Por favor, intenta nuevamente.')
    },
    onFinish: () => {
      console.log('🏁 Petición finalizada')
      console.log('=== FIN TOGGLE OPTIMISTA ===')
    }
  })
}
</script>
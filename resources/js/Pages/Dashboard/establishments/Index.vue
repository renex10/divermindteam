<script setup>
/* resources\js\Pages\Dashboard\establishments\Index.vue */
/**
 * IMPORTACIONES NECESARIAS
 */
import { ref, computed, watch } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import EstablishmentTable from '@/Components/Table/Establishment.vue'
import CreateEstablishmentModal from '@/Components/Modal/CreateEstablishmentModal.vue'

/**
 * OBTENER DATOS DESDE EL OBJETO GLOBAL DE LA PÁGINA
 */
const page = usePage()

// 🏢 MODIFICACIÓN: Crear ref reactivo para manejar establecimientos localmente
const establishments = ref([...(page.props.establishments?.data || [])])

// 📄 Información de la paginación
const pagination = computed(() => page.props.establishments?.pagination || {
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null
})

// 🌍 Listas de regiones y comunas
const regiones = computed(() => page.props.regiones || [])
const comunas = computed(() => page.props.comunas || [])

// 🔍 Filtros de búsqueda
const filters = computed(() => page.props.filters || {})
const busqueda = ref(filters.value.search || '')

/**
 * CONTROL DE ESTADO DEL MODAL
 */
const modalAbierto = ref(false)

function abrirModal() {
  modalAbierto.value = true
}

function cerrarModal() {
  modalAbierto.value = false
}

/**
 * 🔍 FUNCIONES DE BÚSQUEDA
 */
// Función para realizar la búsqueda
const realizarBusqueda = () => {
  router.get(route('establishments.index'), {
    search: busqueda.value
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['establishments', 'filters'],
    onSuccess: (page) => {
      // Actualizar la lista local con los nuevos datos
      establishments.value = page.props.establishments?.data || []
    }
  })
}

// Búsqueda con debounce para evitar demasiadas peticiones
let timeoutId = null
const buscarConDebounce = () => {
  if (timeoutId) {
    clearTimeout(timeoutId)
  }
  
  timeoutId = setTimeout(() => {
    realizarBusqueda()
  }, 500) // Esperar 500ms después de que el usuario deje de escribir
}

// Watcher para detectar cambios en la búsqueda
watch(busqueda, () => {
  buscarConDebounce()
})

// Función para limpiar la búsqueda
const limpiarBusqueda = () => {
  busqueda.value = ''
  realizarBusqueda()
}

/**
 * 🆕 NUEVA FUNCIÓN: Agregar establecimiento optimista
 */
function agregarEstablecimiento(nuevoEstablecimiento) {
  console.log('✨ Agregando establecimiento optimista:', nuevoEstablecimiento)
  
  // Si no hay búsqueda activa, agregar al inicio de la lista
  if (!busqueda.value) {
    establishments.value.unshift(nuevoEstablecimiento)
  }
  
  // Recargar desde el servidor para sincronizar
  setTimeout(() => {
    router.reload({ only: ['establishments'] })
  }, 100)
}

/**
 * 🆕 NUEVA FUNCIÓN: Actualizar establecimiento optimista
 */
function actualizarEstablecimiento(id, datosActualizados) {
  console.log('🔄 Actualizando establecimiento optimista:', id, datosActualizados)
  
  const index = establishments.value.findIndex(e => e.id === id)
  if (index !== -1) {
    // Actualizar solo los campos que cambiaron
    establishments.value[index] = { 
      ...establishments.value[index], 
      ...datosActualizados 
    }
  }
}

/**
 * RECARGA DE LA LISTA (fallback si es necesario)
 */
function refrescar() {
  router.reload({ only: ['establishments'] })
}

/**
 * FUNCIÓN PARA CAMBIAR DE PÁGINA
 */
function changePage(pageNumber) {
  if (pageNumber < 1 || pageNumber > pagination.value.last_page) return

  router.get(route('establishments.index'), {
    page: pageNumber,
    search: busqueda.value // Mantener la búsqueda al cambiar de página
  }, {
    preserveState: true,
    replace: true,
    onSuccess: (page) => {
      // Actualizar la lista local con los nuevos datos
      establishments.value = page.props.establishments?.data || []
    }
  })
}

// 🪵 DEBUG
console.log('🏗️ Establecimientos iniciales:', establishments.value)
console.log('📋 Regiones disponibles:', regiones.value)
console.log('🏘️ Comunas disponibles:', comunas.value)
console.log('🔍 Filtros actuales:', filters.value)
</script>

<template>
  <DashboardLayout>
    <!-- 🧭 Encabezado del módulo -->
    <template #header>
      <h1 class="text-2xl font-bold">Establecimientos</h1>
    </template>

    <div class="space-y-6">
      <!-- 🔍 Barra de búsqueda -->
      <div class="bg-white p-4 rounded-lg shadow-sm border">
        <div class="flex items-center gap-4">
          <div class="relative flex-1">
            <input
              v-model="busqueda"
              type="text"
              placeholder="Buscar por nombre, RBD, dirección, comuna o región..."
              class="border border-gray-300 rounded-lg px-4 py-2 w-full pr-10 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
            
            <!-- Botón para limpiar búsqueda -->
            <button
              v-if="busqueda"
              @click="limpiarBusqueda"
              class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
              title="Limpiar búsqueda"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <!-- Indicador de resultados -->
          <div class="text-sm text-gray-600 whitespace-nowrap">
            <span v-if="busqueda">
              <strong>{{ pagination.total }}</strong> resultado{{ pagination.total === 1 ? '' : 's' }} 
              para "<em>{{ busqueda }}</em>"
            </span>
            <span v-else>
              <strong>{{ pagination.total }}</strong> establecimiento{{ pagination.total === 1 ? '' : 's' }} total{{ pagination.total === 1 ? '' : 'es' }}
            </span>
          </div>
        </div>
      </div>

      <!-- 🚫 Mensaje si no hay resultados -->
      <div v-if="establishments.length === 0 && busqueda" class="bg-white rounded-lg shadow-sm border p-8">
        <div class="text-center">
          <div class="text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron resultados</h3>
            <p class="mt-1 text-sm text-gray-500">
              No hay establecimientos que coincidan con "<strong>{{ busqueda }}</strong>"
            </p>
            <div class="mt-6">
              <button
                @click="limpiarBusqueda"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Limpiar búsqueda
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- 🧾 Tabla de establecimientos -->
      <div v-else>
        <EstablishmentTable 
          :establishments="establishments" 
          @openModal="abrirModal"
          @establecimiento-actualizado="actualizarEstablecimiento"
        />
      </div>

      <!-- 🔢 Controles de paginación -->
      <div v-if="pagination.last_page > 1 && establishments.length > 0" class="mt-6 flex justify-center items-center space-x-4">
        <button
          class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          :disabled="!pagination.prev_page_url"
          @click="changePage(pagination.current_page - 1)"
        >
          ← Anterior
        </button>
        
        <span class="text-sm text-gray-600">
          Página {{ pagination.current_page }} de {{ pagination.last_page }}
        </span>
        
        <button
          class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          :disabled="!pagination.next_page_url"
          @click="changePage(pagination.current_page + 1)"
        >
          Siguiente →
        </button>
      </div>

      <!-- 🆕 Modal para crear establecimiento -->
      <CreateEstablishmentModal
        :isOpen="modalAbierto"
        :regiones="regiones"
        :comunas="comunas"
        @close="cerrarModal"
        @saved="agregarEstablecimiento"
      />
    </div>
  </DashboardLayout>
</template>
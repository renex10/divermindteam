<script setup>
/* resources\js\Pages\Dashboard\establishments\Index.vue */
/**
 * IMPORTACIONES NECESARIAS
 */
import { ref, computed } from 'vue'
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
 * 🆕 NUEVA FUNCIÓN: Agregar establecimiento optimista
 */
function agregarEstablecimiento(nuevoEstablecimiento) {
  console.log('✨ Agregando establecimiento optimista:', nuevoEstablecimiento)
  
  // Agregar al inicio de la lista para que aparezca primero
  establishments.value.unshift(nuevoEstablecimiento)
  
  // Opcional: Recargar desde el servidor para sincronizar
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
    page: pageNumber
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
</script>

<template>
  <DashboardLayout>
    <!-- 🧭 Encabezado del módulo -->
    <template #header>
      <h1 class="text-2xl font-bold">Establecimientos</h1>
    </template>

    <div class="space-y-6">
      <!-- 🧾 Tabla de establecimientos -->
      <EstablishmentTable 
        :establishments="establishments" 
        @openModal="abrirModal"
        @establecimiento-actualizado="actualizarEstablecimiento"
      />

      <!-- 🆕 Modal para crear establecimiento -->
      <CreateEstablishmentModal
        :isOpen="modalAbierto"
        :regiones="regiones"
        :comunas="comunas"
        @close="cerrarModal"
        @saved="agregarEstablecimiento"
      />

      <!-- 🔢 Controles de paginación -->
      <div class="mt-4 flex justify-center items-center space-x-4">
        <button
          class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="!pagination.prev_page_url"
          @click="changePage(pagination.current_page - 1)"
        >
          ← Anterior
        </button>
        
        <span class="text-sm text-gray-600">
          Página {{ pagination.current_page }} de {{ pagination.last_page }}
        </span>
        
        <button
          class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="!pagination.next_page_url"
          @click="changePage(pagination.current_page + 1)"
        >
          Siguiente →
        </button>
      </div>
    </div>
  </DashboardLayout>
</template>
<script setup>
/* resources\js\Pages\Dashboard\establishments\Index.vue */
/**
 * IMPORTACIONES NECESARIAS
 */
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3' // <-- Importamos router para navegación Inertia en Vue 3
// import { Inertia } from '@inertiajs/inertia' // <-- QUITADA para evitar conflicto y error "Inertia is not defined"

import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import EstablishmentTable from '@/Components/Table/Establishment.vue'
import CreateEstablishmentModal from '@/Components/Modal/CreateEstablishmentModal.vue'

/**
 * OBTENER DATOS DESDE EL OBJETO GLOBAL DE LA PÁGINA (props enviados desde el backend Laravel)
 */
const page = usePage()

// 🏢 Lista de establecimientos paginados (solo el array `data`)
const establishments = computed(() => page.props.establishments?.data || [])

// 📄 Información de la paginación (actual, última página, etc.)
const pagination = computed(() => page.props.establishments?.pagination || {
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null
})

// 🌍 Listas de regiones y comunas (relacionadas a los establecimientos)
const regiones = computed(() => page.props.regiones || [])
const comunas = computed(() => page.props.comunas || [])

/**
 * CONTROL DE ESTADO DEL MODAL
 */
// 🔓 Controla si el modal está abierto
const modalAbierto = ref(false)

// 🔘 Abre el modal
function abrirModal() {
  modalAbierto.value = true
}

// 🔒 Cierra el modal
function cerrarModal() {
  modalAbierto.value = false
}

/**
 * RECARGA DE LA LISTA DESPUÉS DE GUARDAR
 */
// 🔁 MODIFICACIÓN IMPORTANTE:
// Cambié Inertia.reload() por router.reload() para evitar el error "Inertia is not defined"
// router.reload() recarga solo la parte especificada sin refrescar toda la página
function refrescar() {
  router.reload({ only: ['establishments'] })
}

/**
 * FUNCIÓN PARA CAMBIAR DE PÁGINA EN LA TABLA (maneja la paginación)
 */
// 🔁 Cambio también Inertia.get() por router.get()
function changePage(pageNumber) {
  if (pageNumber < 1 || pageNumber > pagination.value.last_page) return

  router.get(route('establishments.index'), {
    page: pageNumber
  }, {
    preserveState: true,
    replace: true
  })
}

// 🪵 DEBUG opcional
console.log('Regiones disponibles:', regiones.value)
console.log('Comunas disponibles:', comunas.value)
</script>

<template>
  <DashboardLayout>
    <!-- 🧭 Encabezado del módulo -->
    <template #header>
      <h1 class="text-2xl font-bold">Establecimientos</h1>
    </template>

    <div class="space-y-6">
      <!-- 🧾 Tabla de establecimientos con botón para abrir el modal -->
      <EstablishmentTable 
        :establishments="establishments" 
        @openModal="abrirModal" 
      />

      <!-- 🆕 Modal para crear establecimiento -->
      <CreateEstablishmentModal
        :isOpen="modalAbierto"
        :regiones="regiones"
        :comunas="comunas"
        @close="cerrarModal"
        @saved="refrescar" 
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

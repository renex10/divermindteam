// VERSIÓN CORREGIDA DEL ARCHIVO Index.vue
// resources/js/Pages/Dashboard/establishments/Index.vue

<script setup>
import { ref, computed } from 'vue'
import EstablishmentTable from '@/Components/Table/Establishment.vue'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

// Usar usePage de forma más robusta
const page = usePage()

// Asegurar que los datos estén disponibles con valores por defecto
const establishments = computed(() => {
  return page.props.establishments?.data || []
})

const pagination = computed(() => {
  return page.props.establishments?.pagination || {
    current_page: 1,
    last_page: 1,
    prev_page_url: null,
    next_page_url: null
  }
})

// Función para cambiar página
function changePage(pageNumber) {
  if (pageNumber < 1 || pageNumber > pagination.value.last_page) return
  
  Inertia.get(route('establishments.index'), { 
    page: pageNumber 
  }, { 
    preserveState: true, 
    replace: true 
  })
}
</script>

<template>
  <DashboardLayout>
    <template #header>
      <h1 class="text-2xl font-bold">Establecimientos</h1>
    </template>
    
    <div class="space-y-6">
      <!-- Tabla que recibe establecimientos para mostrar -->
      <EstablishmentTable :establishments="establishments" />
      
      <!-- Paginador mejorado -->
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
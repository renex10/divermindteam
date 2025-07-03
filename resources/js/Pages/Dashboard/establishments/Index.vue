<script setup>
import { ref } from 'vue'
import EstablishmentTable from '@/Components/Table/Establishment.vue'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'  // <-- Importar layout

const page = usePage()

const establishments = ref(page.props.establishments.data || [])
const pagination = ref(page.props.establishments || {})

function changePage(page) {
  Inertia.get(route('establishments.index'), { page }, { preserveState: true, replace: true })
}
</script>

<template>
  <DashboardLayout>
    <template #header>
      <h1 class="text-2xl font-bold">Establecimientos</h1>
    </template>

    <div>
      <!-- Tabla que recibe establecimientos para mostrar -->
      <EstablishmentTable :establishments="establishments" />

      <!-- Paginador simple -->
      <div class="mt-4 flex justify-center space-x-2">
        <button
          class="px-3 py-1 border rounded disabled:opacity-50"
          :disabled="!pagination.prev_page_url"
          @click="changePage(pagination.current_page - 1)"
        >
          Anterior
        </button>

        <span>Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>

        <button
          class="px-3 py-1 border rounded disabled:opacity-50"
          :disabled="!pagination.next_page_url"
          @click="changePage(pagination.current_page + 1)"
        >
          Siguiente
        </button>
      </div>
    </div>
  </DashboardLayout>
</template>

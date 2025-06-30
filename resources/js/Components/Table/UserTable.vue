<template>
  <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <table class="min-w-full divide-y divide-gray-300">
      <thead class="bg-gray-50">
        <tr>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Usuario
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Email
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Establecimiento
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Comuna
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Región
          </th>
          <th scope="col" class="relative px-6 py-3">
            <span class="sr-only">Acciones</span>
          </th>
        </tr>
      </thead>

      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-if="!usersData || usersData.length === 0">
          <td colspan="6" class="px-6 py-12 text-center text-gray-500">
            <div class="flex flex-col items-center">
              <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                </path>
              </svg>
              <p class="text-lg font-medium">No hay usuarios registrados</p>
            </div>
          </td>
        </tr>

        <tr v-for="user in usersData" :key="user.id" class="hover:bg-gray-50">
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10">
                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                  <span class="text-sm font-medium text-white">
                    {{ getInitials(user.name, user.last_name) }}
                  </span>
                </div>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">
                  {{ user.name }} {{ user.last_name || '' }}
                </div>
              </div>
            </div>
          </td>

          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ user.email || 'N/A' }}</div>
          </td>

          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ user.establishment?.name || 'Sin asignar' }}</div>
          </td>

          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ user.establishment?.commune?.name || 'N/A' }}</div>
          </td>

          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ user.establishment?.commune?.region?.name || 'N/A' }}</div>
          </td>

          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex items-center justify-end space-x-2">
              <button @click="$emit('view', user)" class="text-indigo-600 hover:text-indigo-900 p-1 rounded"
                title="Ver detalles">
                <EyeIcon class="w-4 h-4" />
              </button>

              <button @click="$emit('edit', user)" class="text-yellow-600 hover:text-yellow-900 p-1 rounded"
                title="Editar">
                <PencilIcon class="w-4 h-4" />
              </button>

              <button @click="$emit('delete', user)" class="text-red-600 hover:text-red-900 p-1 rounded"
                title="Eliminar">
                <TrashIcon class="w-4 h-4" />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="pagination && pagination.last_page > 1"
      class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
      <div class="flex-1 flex justify-between sm:hidden">
        <button @click="goToPage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1"
          :class="pagination.current_page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
          Anterior
        </button>

        <button @click="goToPage(pagination.current_page + 1)"
          :disabled="pagination.current_page >= pagination.last_page"
          :class="pagination.current_page >= pagination.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
          Siguiente
        </button>
      </div>

      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Mostrando
            <span class="font-medium">{{ pagination.from }}</span>
            a
            <span class="font-medium">{{ pagination.to }}</span>
            de
            <span class="font-medium">{{ pagination.total }}</span>
            resultados
          </p>
        </div>

        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <button @click="goToPage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1"
              :class="pagination.current_page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                  clip-rule="evenodd" />
              </svg>
            </button>

            <template v-for="page in getPageNumbers()" :key="page">
              <button v-if="page !== '...'" @click="goToPage(page)" :class="page === pagination.current_page ?
                'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' :
                'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                {{ page }}
              </button>
              <span v-else
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                ...
              </span>
            </template>

            <button @click="goToPage(pagination.current_page + 1)"
              :disabled="pagination.current_page >= pagination.last_page"
              :class="pagination.current_page >= pagination.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
              class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd" />
              </svg>
            </button>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
// Importamos los iconos de Heroicons
import { EyeIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'

// Definición de props que recibe el componente.
// Ahora espera 'users' en lugar de 'students'.
const props = defineProps({
  users: {
    type: [Object, Array],
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  }
})

// Definición de eventos que el componente puede emitir.
const emit = defineEmits(['view', 'edit', 'delete', 'page-change'])

// Computed para extraer los datos de usuarios del objeto de paginación.
// Se adaptó para que reciba el prop 'users' en lugar de 'studentsData'.
const usersData = computed(() => {
  if (props.loading) return [] // Retornar array vacío durante la carga

  // Si users es un array, lo devolvemos directamente (para casos sin paginación).
  if (Array.isArray(props.users)) {
    return props.users
  }

  // Si es un objeto con 'data' (estructura de Laravel pagination), extraemos 'data'.
  if (props.users && props.users.data) {
    return props.users.data
  }

  // Si no tiene la estructura esperada, devolvemos array vacío.
  return []
})

// Computed para extraer información de paginación (sin cambios, reutilizable).
const pagination = computed(() => {
  if (!Array.isArray(props.users) && props.users) {
    return {
      current_page: props.users.current_page,
      last_page: props.users.last_page,
      from: props.users.from,
      to: props.users.to,
      total: props.users.total,
      per_page: props.users.per_page
    }
  }
  return null
})

// Función para obtener las iniciales del nombre (sin cambios, es genérica).
const getInitials = (name, lastName) => {
  const firstInitial = name ? name.charAt(0).toUpperCase() : ''
  const lastInitial = lastName ? lastName.charAt(0).toUpperCase() : ''
  return firstInitial + lastInitial
}

// Funciones para badges (eliminadas porque la tabla de usuarios no las necesita,
// pero puedes adaptarlas si un usuario tiene un "estado" o "prioridad").
// Si no las necesitas, puedes eliminar estas funciones y sus llamadas en el template.
const needTypeBadgeClass = (needType) => needType === 'permanent' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'
const priorityBadgeClass = (priority) => {
  switch (priority) {
    case 1: return 'bg-red-100 text-red-800'
    case 2: return 'bg-yellow-100 text-yellow-800'
    case 3: return 'bg-green-100 text-green-800'
    default: return 'bg-gray-100 text-gray-800'
  }
}
const getPriorityText = (priority) => {
  switch (priority) {
    case 1: return 'Máxima'
    case 2: return 'Media'
    case 3: return 'Básica'
    default: return 'Sin definir'
  }
}

// Lógica de paginación (sin cambios, reutilizable).
const goToPage = (page) => {
  if (page >= 1 && pagination.value && page <= pagination.value.last_page && page !== pagination.value.current_page) {
    emit('page-change', page)
  }
}

const getPageNumbers = () => {
  if (!pagination.value) return []
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const pages = []
  if (last <= 7) {
    for (let i = 1; i <= last; i++) { pages.push(i) }
    return pages
  }
  pages.push(1)
  let start = Math.max(2, current - 1)
  let end = Math.min(last - 1, current + 1)
  if (current <= 3) { end = 5 } else if (current >= last - 2) { start = last - 4 }
  if (start > 2) { pages.push('...') }
  for (let i = start; i <= end; i++) { if (i > 1 && i < last) { pages.push(i) } }
  if (end < last - 1) { pages.push('...') }
  if (last > 1) { pages.push(last) }
  return pages
}

</script>
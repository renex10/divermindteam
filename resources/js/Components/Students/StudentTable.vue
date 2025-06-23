<template>
  <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <!-- Tabla de estudiantes -->
    <table class="min-w-full divide-y divide-gray-300">
      <!-- Encabezado de la tabla -->
      <thead class="bg-gray-50">
        <tr>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Estudiante
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Email
          </th>
          <!-- Cambiado: Fecha Nacimiento por Apellido -->
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Apellido
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Tipo Necesidad
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Prioridad
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Estado
          </th>
          <th scope="col" class="relative px-6 py-3">
            <span class="sr-only">Acciones</span>
          </th>
        </tr>
      </thead>
      
      <!-- Cuerpo de la tabla -->
      <tbody class="bg-white divide-y divide-gray-200">
        <!-- Verificamos si hay estudiantes para mostrar -->
        <tr v-if="!studentsData || studentsData.length === 0">
          <!-- Cambiado: colspan de 8 a 7 -->
          <td colspan="7" class="px-6 py-12 text-center text-gray-500">
            <div class="flex flex-col items-center">
              <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
              <p class="text-lg font-medium">No hay estudiantes registrados</p>
            </div>
          </td>
        </tr>
        
        <!-- Iteramos sobre cada estudiante -->
        <tr v-for="student in studentsData" :key="student.id" class="hover:bg-gray-50">
          <!-- Nombre completo del estudiante -->
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10">
                <!-- Avatar con iniciales -->
                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                  <span class="text-sm font-medium text-white">
                    {{ student.initials || getInitials(student.user?.name, student.user?.last_name) }}
                  </span>
                </div>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">
                  {{ student.display_name || student.user?.full_name || `${student.user?.name || ''} ${student.user?.last_name || ''}`.trim() }}
                </div>
              </div>
            </div>
          </td>
          
          <!-- Email del estudiante -->
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ student.user?.email || 'N/A' }}</div>
          </td>
          
          <!-- Cambiado: Fecha de nacimiento por Apellido -->
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ student.user?.last_name || 'N/A' }}</div>
          </td>
          
          <!-- Tipo de necesidad con badge -->
          <td class="px-6 py-4 whitespace-nowrap">
            <span :class="needTypeBadgeClass(student.need_type)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
              {{ student.need_type_text || (student.need_type === 'permanent' ? 'Permanente' : 'Temporal') }}
            </span>
          </td>
          
          <!-- Prioridad con badge colorizado -->
          <td class="px-6 py-4 whitespace-nowrap">
            <span :class="priorityBadgeClass(student.priority)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
              {{ student.priority_text || getPriorityText(student.priority) }}
            </span>
          </td>
          
          <!-- Estado activo/inactivo -->
          <td class="px-6 py-4 whitespace-nowrap">
            <span :class="student.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
              {{ student.status_text || (student.active ? 'Activo' : 'Inactivo') }}
            </span>
          </td>
          
          <!-- Botones de acciones con Heroicons -->
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex items-center justify-end space-x-2">
              <!-- Botón Ver -->
              <button @click="$emit('view', student)" 
                      class="text-indigo-600 hover:text-indigo-900 p-1 rounded" 
                      title="Ver detalles">
                <EyeIcon class="w-4 h-4" />
              </button>
              
              <!-- Botón Editar -->
              <button @click="$emit('edit', student)" 
                      class="text-yellow-600 hover:text-yellow-900 p-1 rounded" 
                      title="Editar">
                <PencilIcon class="w-4 h-4" />
              </button>
              
              <!-- Botón Eliminar -->
              <button @click="$emit('delete', student)" 
                      class="text-red-600 hover:text-red-900 p-1 rounded" 
                      title="Eliminar">
                <TrashIcon class="w-4 h-4" />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Componente de paginación -->
    <div v-if="pagination && pagination.last_page > 1" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
      <!-- Información de resultados (móvil) -->
      <div class="flex-1 flex justify-between sm:hidden">
        <button @click="goToPage(pagination.current_page - 1)" 
                :disabled="pagination.current_page <= 1"
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

      <!-- Información de resultados (desktop) -->
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
        
        <!-- Controles de navegación -->
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <!-- Botón Anterior -->
            <button @click="goToPage(pagination.current_page - 1)" 
                    :disabled="pagination.current_page <= 1"
                    :class="pagination.current_page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </button>

            <!-- Números de página -->
            <template v-for="page in getPageNumbers()" :key="page">
              <button v-if="page !== '...'" 
                      @click="goToPage(page)"
                      :class="page === pagination.current_page ? 
                        'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 
                        'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                      class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                {{ page }}
              </button>
              <span v-else class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                ...
              </span>
            </template>

            <!-- Botón Siguiente -->
            <button @click="goToPage(pagination.current_page + 1)" 
                    :disabled="pagination.current_page >= pagination.last_page"
                    :class="pagination.current_page >= pagination.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
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

// Definición de props que recibe el componente
const props = defineProps({
  students: {
    type: [Object, Array],
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  }
})

// Definición de eventos que el componente puede emitir
const emit = defineEmits(['view', 'edit', 'delete', 'page-change'])

// Computed para extraer los datos de estudiantes del objeto de paginación
const studentsData = computed(() => {
  if (props.loading) return [] // Retornar array vacío durante la carga
  
  // Si students es un array, lo devolvemos directamente
  if (Array.isArray(props.students)) {
    return props.students
  }
  
  // Si es un objeto con data (estructura de Laravel pagination), extraemos data
  if (props.students && props.students.data) {
    return props.students.data
  }
  
  // Si no tiene la estructura esperada, devolvemos array vacío
  return []
})

// Computed para extraer información de paginación
const pagination = computed(() => {
  // Solo devolvemos paginación si students es un objeto con meta de paginación
  if (!Array.isArray(props.students) && props.students) {
    return {
      current_page: props.students.current_page,
      last_page: props.students.last_page,
      from: props.students.from,
      to: props.students.to,
      total: props.students.total,
      per_page: props.students.per_page
    }
  }
  return null
})

// Función para obtener las iniciales del nombre
const getInitials = (name, lastName) => {
  const firstInitial = name ? name.charAt(0).toUpperCase() : ''
  const lastInitial = lastName ? lastName.charAt(0).toUpperCase() : ''
  return firstInitial + lastInitial
}

// Función para obtener clase CSS del badge de tipo de necesidad
const needTypeBadgeClass = (needType) => {
  return needType === 'permanent' 
    ? 'bg-purple-100 text-purple-800' 
    : 'bg-blue-100 text-blue-800'
}

// Función para obtener clase CSS del badge de prioridad
const priorityBadgeClass = (priority) => {
  switch (priority) {
    case 1:
      return 'bg-red-100 text-red-800'      // Prioridad máxima - rojo
    case 2:
      return 'bg-yellow-100 text-yellow-800' // Prioridad media - amarillo
    case 3:
      return 'bg-green-100 text-green-800'   // Prioridad básica - verde
    default:
      return 'bg-gray-100 text-gray-800'     // Por defecto - gris
  }
}

// Función para obtener texto de prioridad
const getPriorityText = (priority) => {
  switch (priority) {
    case 1:
      return 'Máxima'
    case 2:
      return 'Media'
    case 3:
      return 'Básica'
    default:
      return 'Sin definir'
  }
}

// Función para ir a una página específica
const goToPage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page && page !== pagination.value.current_page) {
    emit('page-change', page)
  }
}

// Función para generar números de página con elipsis
const getPageNumbers = () => {
  if (!pagination.value) return []
  
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const pages = []
  
  // Si hay 7 páginas o menos, mostrar todas
  if (last <= 7) {
    for (let i = 1; i <= last; i++) {
      pages.push(i)
    }
    return pages
  }
  
  // Siempre mostrar primera página
  pages.push(1)
  
  // Determinar rango de páginas a mostrar alrededor de la actual
  let start = Math.max(2, current - 1)
  let end = Math.min(last - 1, current + 1)
  
  // Ajustar rango si estamos cerca del inicio o final
  if (current <= 3) {
    end = 5
  } else if (current >= last - 2) {
    start = last - 4
  }
  
  // Agregar elipsis si hay un gap después de la primera página
  if (start > 2) {
    pages.push('...')
  }
  
  // Agregar páginas del rango
  for (let i = start; i <= end; i++) {
    if (i > 1 && i < last) {
      pages.push(i)
    }
  }
  
  // Agregar elipsis si hay un gap antes de la última página
  if (end < last - 1) {
    pages.push('...')
  }
  
  // Siempre mostrar última página
  if (last > 1) {
    pages.push(last)
  }
  
  return pages
}
</script>
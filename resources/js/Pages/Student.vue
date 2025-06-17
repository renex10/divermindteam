<template>
  <DashboardLayout title="Gestión de Estudiantes">
    <template #header>
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">
          Lista de Estudiantes
        </h1>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
          + Nuevo Estudiante
        </button>
      </div>
    </template>

    <div class="space-y-6">
      <!-- Cards de estadísticas -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 rounded-lg shadow text-white">
          <h3 class="text-lg font-medium">Total Estudiantes</h3>
          <!-- Usamos el total de la paginación, no el length del array actual -->
          <p class="text-3xl font-bold mt-2">{{ students.total || 0 }}</p>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-lg shadow text-white">
          <h3 class="text-lg font-medium">Estudiantes Activos</h3>
          <!-- Calculamos solo sobre los estudiantes de la página actual -->
          <p class="text-3xl font-bold mt-2">{{ activeStudentsCount }}</p>
        </div>
        
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 p-6 rounded-lg shadow text-white">
          <h3 class="text-lg font-medium">Prioridad Alta</h3>
          <!-- Calculamos solo sobre los estudiantes de la página actual -->
          <p class="text-3xl font-bold mt-2">{{ highPriorityCount }}</p>
        </div>
      </div>

      <!-- Tabla de estudiantes -->
      <div class="bg-white rounded-lg shadow">
        <StudentTable 
          :students="students" 
          @view="handleView"
          @edit="handleEdit"
          @delete="handleDelete"
          @page-change="handlePageChange"
        />
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'  // Importación correcta para Inertia v1.0+
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import StudentTable from '@/Components/Students/StudentTable.vue'

// Definir props que recibe el componente desde el controlador
const props = defineProps({
  students: {
    type: Object, // Objeto de paginación de Laravel
    required: true
  }
})

// Verificar estructura de datos en consola para debugging
console.log("Datos de estudiantes:", props.students)

// Computed para obtener datos de los estudiantes de la página actual
const currentStudents = computed(() => {
  return props.students.data || []
})

// Computed para contar estudiantes activos en la página actual
const activeStudentsCount = computed(() => {
  return currentStudents.value.filter(student => student.active).length
})

// Computed para contar estudiantes con prioridad alta en la página actual
const highPriorityCount = computed(() => {
  return currentStudents.value.filter(student => student.priority === 1).length
})

// Función para manejar cambio de página
const handlePageChange = (page) => {
  console.log('Cambiando a página:', page)
  
  // Navegar a la nueva página manteniendo el estado (Inertia v1.0+)
  router.get(route('students.index'), 
    { page }, // Parámetros de query
    {
      preserveState: true, // Mantener el estado actual
      replace: true        // Reemplazar la entrada del historial
    }
  )
}

// Función para ver detalles de un estudiante
const handleView = (student) => {
  console.log('Ver estudiante:', student)
  // Aquí puedes implementar la lógica para ver detalles
  // Por ejemplo, abrir un modal o navegar a una página de detalles
  
  // Ejemplo de navegación (descomenta si tienes la ruta):
  // router.visit(route('students.show', student.id))
}

// Función para editar un estudiante
const handleEdit = (student) => {
  console.log('Editar estudiante:', student)
  // Aquí puedes implementar la lógica para editar
  // Por ejemplo, navegar a una página de edición
  
  // Ejemplo de navegación (descomenta si tienes la ruta):
  // router.visit(route('students.edit', student.id))
}

// Función para eliminar un estudiante
const handleDelete = (student) => {
  console.log('Eliminar estudiante:', student)
  
  // Mostrar confirmación antes de eliminar
  if (confirm(`¿Estás seguro de que quieres eliminar a ${student.user?.name} ${student.user?.last_name}?`)) {
    // Realizar petición DELETE (descomenta si tienes la ruta):
    /*
    router.delete(route('students.destroy', student.id), {
      onSuccess: () => {
        // Opcional: mostrar mensaje de éxito
        console.log('Estudiante eliminado correctamente')
      },
      onError: () => {
        // Opcional: mostrar mensaje de error
        console.log('Error al eliminar estudiante')
      }
    })
    */
  }
}
</script>
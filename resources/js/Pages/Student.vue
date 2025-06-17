<template>
  <DashboardLayout title="Gestión de Estudiantes">
    <template #header>
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">
          Lista de Estudiantes
        </h1>
        <button @click="openModal"
          class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 flex items-center gap-1">
          <PlusIcon class="h-5 w-5" />
          Nuevo Estudiante
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
        <StudentTable :students="students" @view="handleView" @edit="handleEdit" @delete="handleDelete"
          @page-change="handlePageChange" />
      </div>
    </div>

 <FormModal
      :isOpen="showModal"
      @close="closeModal"
      @submit="handleFormSubmit"
    />
  </DashboardLayout>
</template>

<script setup>
import { computed, ref } from 'vue'; // Importación corregida
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import StudentTable from '@/Components/Students/StudentTable.vue'
import FormModal from '@/Components/Modal/FormModal.vue';

// Definir props que recibe el componente desde el controlador
const props = defineProps({
  students: {
    type: Object,
    required: true
  }
})

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
  router.get(route('students.index'),
    { page },
    {
      preserveState: true,
      replace: true
    }
  )
}

// Funciones para manejar acciones
const handleView = (student) => {
  // Lógica para ver estudiante
}

const handleEdit = (student) => {
  // Lógica para editar estudiante
}

const handleDelete = (student) => {
  // Lógica para eliminar estudiante
}

const showModal = ref(false);

const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const handleFormSubmit = (formData) => {
  console.log('Datos del formulario:', formData);
  // Aquí enviarías los datos al servidor
  // Ejemplo con Inertia:
  // router.post(route('students.store'), formData, {
  //   onSuccess: () => {
  //     closeModal();
  //   }
  // });
};
</script>
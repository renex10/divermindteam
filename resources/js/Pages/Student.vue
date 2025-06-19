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
          <p class="text-3xl font-bold mt-2">{{ students.total || 0 }}</p>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-lg shadow text-white">
          <h3 class="text-lg font-medium">Estudiantes Activos</h3>
          <p class="text-3xl font-bold mt-2">{{ activeStudentsCount }}</p>
        </div>

        <div class="bg-gradient-to-r from-amber-500 to-orange-600 p-6 rounded-lg shadow text-white">
          <h3 class="text-lg font-medium">Prioridad Alta</h3>
          <p class="text-3xl font-bold mt-2">{{ highPriorityCount }}</p>
        </div>
      </div>

      <!-- Tabla de estudiantes -->
      <div class="bg-white rounded-lg shadow">
        <StudentTable :students="students" @view="handleView" @edit="handleEdit" @delete="handleDelete"
          @page-change="handlePageChange" />
      </div>
    </div>

    <!-- Modal reemplazado por la nueva estructura -->
    <StudentFormModal
      :isOpen="showModal"
      :initialData="initialFormData"
      :externalData="formExternalData"
      @close="closeModal"
      @submit="handleFormSubmit"
    />
  </DashboardLayout>
</template>

<script setup>
import { computed, ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StudentTable from '@/Components/Students/StudentTable.vue';
import StudentFormModal from '@/Components/Modal/StudentFormModal.vue';

// Definir props que recibe el componente desde el controlador
const props = defineProps({
  students: {
    type: Object,
    required: true
  },
  // Agregamos props para cursos y especialistas
  courses: {
    type: Array,
    default: () => []
  },
  specialists: {
    type: Array,
    default: () => []
  }
});

// Computed para obtener datos de los estudiantes de la página actual
const currentStudents = computed(() => {
  return props.students.data || [];
});

// Computed para contar estudiantes activos en la página actual
const activeStudentsCount = computed(() => {
  return currentStudents.value.filter(student => student.active).length;
});

// Computed para contar estudiantes con prioridad alta en la página actual
const highPriorityCount = computed(() => {
  return currentStudents.value.filter(student => student.priority === 1).length;
});

// Función para manejar cambio de página
const handlePageChange = (page) => {
  router.get(route('students.index'),
    { page },
    {
      preserveState: true,
      replace: true
    }
  );
};

// Funciones para manejar acciones
const handleView = (student) => {
  // Lógica para ver estudiante
};

const handleEdit = (student) => {
  // Lógica para editar estudiante
};

const handleDelete = (student) => {
  // Lógica para eliminar estudiante
};

const showModal = ref(false);
const initialFormData = ref({});
const formExternalData = reactive({
  courses: props.courses,
  specialists: props.specialists
});

const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const handleFormSubmit = async (formData) => {
  try {
    const form = new FormData();
    
    // Agregar todos los campos
    for (const key in formData) {
      if (key === 'previous_reports') {
        // Manejar múltiples archivos
        formData[key].forEach(file => {
          form.append('previous_reports[]', file);
        });
      } else if (key === 'medical_report') {
        // Manejar archivo único
        if (formData[key]) {
          form.append('medical_report', formData[key]);
        }
      } else {
        // Manejar campos normales
        form.append(key, formData[key]);
      }
    }
    
    const response = await axios.post(route('students.store'), form);
    
    console.log('Respuesta exitosa:', response.data);
    closeModal();
    router.reload(); // Recargar la lista de estudiantes
    
  } catch (error) {
    console.error('Error en la petición:', error);
    
    if (error.response) {
      console.error('Datos del error:', error.response.data);
      alert(`Error: ${error.response.data.message || error.response.statusText}`);
    } else {
      console.error('Error sin respuesta:', error.message);
      alert('Error de red: ' + error.message);
    }
  }
};
</script>
<template>
<!--   resources\js\Pages\Student.vue -->
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
import { computed, ref, reactive, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StudentTable from '@/Components/Students/StudentTable.vue';
import StudentFormModal from '@/Components/Modal/StudentFormModal.vue';

const props = defineProps({
  students: {
    type: Object,
    required: true
  },
  courses: {
    type: Array,
    default: () => []
  },
  specialists: {
    type: Array,
    default: () => []
  },
  users: {
    type: Array,
    default: () => []
  },
  isCreateView: {
    type: Boolean,
    default: false
  }
});

onMounted(() => {
  if (props.isCreateView) {
    openModal();
  }
});

const formExternalData = reactive({
  courses: props.courses || [],
  specialists: props.specialists || [],
  users: props.users || []
});

const currentStudents = computed(() => props.students.data || []);
const activeStudentsCount = computed(() => currentStudents.value.filter(student => student.active).length);
const highPriorityCount = computed(() => currentStudents.value.filter(student => student.priority === 1).length);

const handlePageChange = (page) => {
  router.get(route('students.index'), { page }, { preserveState: true, replace: true });
};

const handleView = (student) => {};
const handleEdit = (student) => {};
const handleDelete = (student) => {};

const showModal = ref(false);
const initialFormData = ref({});

const openModal = () => showModal.value = true;
const closeModal = () => showModal.value = false;

const handleFormSubmit = async (formData) => {
  try {
    const form = new FormData();
    
    // Construir objeto new_user
    const newUserData = {
      name: formData.new_user.name,
      last_name: formData.new_user.last_name,
      email: formData.new_user.email,
      password: formData.new_user.password
    };
    
    // Agregar new_user como JSON
    form.append('new_user', JSON.stringify(newUserData));
    
    // Agregar otros campos
    form.append('birth_date', formData.birth_date);
    form.append('course_id', formData.course_id);
    form.append('diagnosis', formData.diagnosis || '');
    form.append('guardian_email', formData.guardian_email || '');
    form.append('need_type', formData.need_type);
    form.append('priority', formData.priority);
    form.append('special_needs', formData.special_needs || '');
    form.append('consent_pie', formData.consent_pie ? '1' : '0');
    form.append('data_processing', formData.data_processing ? '1' : '0');
    form.append('guardian_name', formData.guardian_name || '');
    form.append('guardian_rut', formData.guardian_rut || '');
    form.append('assigned_specialist', formData.assigned_specialist);
    form.append('evaluation_date', formData.evaluation_date);
    form.append('initial_observations', formData.initial_observations || '');
    
    // Archivos
    if (formData.medical_report) {
      form.append('medical_report', formData.medical_report);
    }
    
    if (formData.previous_reports && formData.previous_reports.length) {
      formData.previous_reports.forEach((file, index) => {
        form.append(`previous_reports[${index}]`, file);
      });
    }

    // DEBUG: Mostrar datos que se enviarán
    console.log('Datos a enviar:', {
      new_user: newUserData,
      birth_date: formData.birth_date,
      course_id: formData.course_id,
      // ... otros campos ...
    });

    const response = await axios.post(route('students.store'), form, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    
    console.log('Respuesta exitosa:', response.data);
    closeModal();
    router.reload();
    
  } catch (error) {
    console.error('Error en la petición:', error);
    if (error.response) {
      console.error('Datos del error:', error.response.data);
      const errorMessages = Object.values(error.response.data.errors || {})
        .flat()
        .join('\n');
      alert(`Error:\n${errorMessages || error.response.data.message || error.response.statusText}`);
    } else {
      alert('Error de red: ' + error.message);
    }
  }
};

console.log("Student.vue - Props recibidas:", {
  students: props.students,
  courses: props.courses,
  specialists: props.specialists,
  users: props.users
});

console.log("formExternalData creado:", formExternalData);
</script>
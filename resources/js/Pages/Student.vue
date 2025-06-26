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
        <StatCard 
          title="Total Estudiantes" 
          :value="students.total || 0"
          gradient-classes="bg-gradient-to-r from-indigo-500 to-purple-600"
        />
        
        <StatCard 
          title="Estudiantes Activos" 
          :value="activeStudentsCount"
          gradient-classes="bg-gradient-to-r from-green-500 to-emerald-600"
        />
        
        <StatCard 
          title="Prioridad Alta" 
          :value="highPriorityCount"
          gradient-classes="bg-gradient-to-r from-amber-500 to-orange-600"
        />
      </div>

      <!-- Tabla de estudiantes -->
      <div class="bg-white rounded-lg shadow">
        <StudentTable :students="students" @view="handleView" @edit="handleEdit" @delete="handleDelete"
          @page-change="handlePageChange" />
      </div>
    </div>

    <!-- Modal de perfil -->
    <StudentProfileModal
      :isOpen="showProfileModal"
      :student="selectedStudent"
      @close="showProfileModal = false"
    />

    <!-- Modal de formulario (para crear y editar) -->
    <StudentFormModal
      :isOpen="showModal"
      :initialData="initialFormData"
      :externalData="formExternalData"
      :isEditing="!!editingStudent"  
      @close="closeModal"
      @submit="handleFormSubmit"
    />
  </DashboardLayout>
</template>

<script setup>
import { computed, ref, reactive, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StudentTable from '@/Components/Students/StudentTable.vue';
import StudentFormModal from '@/Components/Modal/StudentFormModal.vue';
import StatCard from '@/Components/Card/StatCard.vue';
import StudentProfileModal from '@/Components/Modal/StudentProfileModal.vue';
import { PlusIcon } from '@heroicons/vue/24/outline';

// Referencia para el estudiante seleccionado en la vista de perfil
const selectedStudent = ref(null);
const showProfileModal = ref(false);

// Referencia para el estudiante que se está editando (null si es creación)
const editingStudent = ref(null);

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

// Variables reactivas para las estadísticas
const stats = reactive({
  total: props.students.total || 0,
  active: 0,
  highPriority: 0
});

// Inicializar con los valores del servidor
stats.active = props.students.data.filter(student => student.active).length;
stats.highPriority = props.students.data.filter(student => student.priority === 1).length;

// Función para obtener las estadísticas actualizadas
const fetchStats = async () => {
  try {
    const response = await axios.get(route('students.stats'));
    stats.total = response.data.total;
    stats.active = response.data.active;
    stats.highPriority = response.data.high_priority;
  } catch (error) {
    console.error('Error obteniendo estadísticas:', error);
  }
};

// Intervalo para actualizar las estadísticas
let statsInterval = null;

onMounted(() => {
  if (props.isCreateView) {
    openModal();
  }
  
  // Actualizar estadísticas cada 30 segundos
  statsInterval = setInterval(fetchStats, 30000);
  
  // También actualizar después de ciertas acciones
  window.addEventListener('student-created', fetchStats);
  window.addEventListener('student-updated', fetchStats);
  window.addEventListener('student-deleted', fetchStats);
});

onUnmounted(() => {
  // Limpiar el intervalo al salir del componente
  clearInterval(statsInterval);
  window.removeEventListener('student-created', fetchStats);
  window.removeEventListener('student-updated', fetchStats);
  window.removeEventListener('student-deleted', fetchStats);
});

const formExternalData = reactive({
  courses: props.courses || [],
  specialists: props.specialists || [],
  users: props.users || []
});

const currentStudents = computed(() => props.students.data || []);

// Actualizar las propiedades computadas para usar las variables reactivas
const activeStudentsCount = computed(() => stats.active);
const highPriorityCount = computed(() => stats.highPriority);

const handlePageChange = (page) => {
  router.get(route('students.index'), { page }, { preserveState: true, replace: true });
};

const handleView = (student) => {
  selectedStudent.value = student;
  showProfileModal.value = true;
};

const handleEdit = (student) => {
  editingStudent.value = student;

  initialFormData.value = {
    new_user: {
      name: student.user.name,
      last_name: student.user.last_name,
      email: student.user.email,
      password: '',
    },
    birth_date: student.birth_date,
    course_id: student.courses?.length > 0 ? student.courses[0].id : null,
    diagnosis: student.diagnosis,
    need_type: student.need_type,
    priority: student.priority,
    special_needs: student.special_needs,
    assigned_specialist: student.assigned_specialist_id,
    evaluation_date: student.evaluation_date,
    initial_observations: student.initial_observations,

    // Campos del apoderado para precarga
    guardian_name: student.guardian_name || '',
    guardian_rut: student.guardian_rut || '',
    relationship: student.relationship || '',
    consent_pie: student.consent_pie ?? false,
    data_processing: student.data_processing ?? false,

    medical_report: null,
    previous_reports: [],
  };

  showModal.value = true;
};

const handleDelete = async (student) => {
  try {
    await axios.delete(route('students.destroy', student.id));
    window.dispatchEvent(new CustomEvent('student-deleted'));
    fetchStats();
    router.reload({ only: ['students'] });
  } catch (error) {
    console.error('Error eliminando estudiante:', error);
    alert('No se pudo eliminar el estudiante');
  }
};

const showModal = ref(false);
const initialFormData = ref({});
const isLoading = ref(false);

const openModal = () => {
  editingStudent.value = null;
  initialFormData.value = {};
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  initialFormData.value = {};
  editingStudent.value = null;
};

const handleFormSubmit = async (formData) => {
  isLoading.value = true;

  try {
    const form = new FormData();
    const newUserData = {
      name: formData.new_user.name,
      last_name: formData.new_user.last_name,
      email: formData.new_user.email,
      ...(formData.new_user.password && { password: formData.new_user.password }),
    };
    form.append('new_user', JSON.stringify(newUserData));

    form.append('birth_date', formData.birth_date);
    form.append('course_id', formData.course_id);
    form.append('diagnosis', formData.diagnosis || '');
    form.append('need_type', formData.need_type);
    form.append('priority', formData.priority);
    form.append('special_needs', formData.special_needs || '');
    form.append('assigned_specialist_id', formData.assigned_specialist);
    form.append('evaluation_date', formData.evaluation_date);
    form.append('initial_observations', formData.initial_observations || '');

    form.append('consent_pie', formData.consent_pie ? '1' : '0');
    form.append('data_processing', formData.data_processing ? '1' : '0');

    if (formData.consent_pie) {
      form.append('guardian_email', formData.guardian_email || '');
      form.append('guardian_name', formData.guardian_name || '');
      form.append('guardian_rut', formData.guardian_rut || '');
      form.append('relationship', formData.relationship || '');
    }

    if (formData.medical_report) {
      form.append('medical_report', formData.medical_report);
    }

    if (formData.previous_reports?.length) {
      formData.previous_reports.forEach((file, index) => {
        form.append(`previous_reports[${index}]`, file);
      });
    }

    const isEditing = editingStudent.value !== null;
    const url = isEditing
      ? route('students.update', editingStudent.value.id)
      : route('students.store');

    if (isEditing) {
      form.append('_method', 'PUT');
    }

    await axios.post(url, form, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    closeModal();
    window.dispatchEvent(
      new CustomEvent(isEditing ? 'student-updated' : 'student-created')
    );

    router.reload({
      only: ['students'],
      preserveState: true,
      onSuccess: () => {
        fetchStats();
      },
    });
  } catch (error) {
    if (error.response?.status === 422) {
      const errors = error.response.data.errors;
      let errorMessages = [];
      for (const field in errors) {
        errorMessages.push(...errors[field]);
      }
      alert(`Errores de validación:\n- ${errorMessages.join('\n- ')}`);
    } else {
      alert('Error al procesar la solicitud. Intenta nuevamente.');
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

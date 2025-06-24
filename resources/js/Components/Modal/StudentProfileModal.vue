<!-- resources/js/Components/Modal/StudentProfileModal.vue -->
<template>
  <BaseModal 
    :isOpen="isOpen" 
    :title="modalTitle" 
    @close="closeModal"
  >
    <div v-if="hasStudentData" class="bg-white rounded-lg">
      <!-- Encabezado con foto y nombre -->
      <div class="flex items-center p-6 border-b border-gray-200">
        <div class="flex-shrink-0 h-20 w-20">
          <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center">
            <span class="text-2xl font-bold text-indigo-600">
              {{ studentInitials }}
            </span>
          </div>
        </div>
        <div class="ml-4">
          <h2 class="text-2xl font-bold text-gray-900">{{ studentFullName }}</h2>
          <div class="flex items-center mt-1">
            <span :class="statusBadgeClass" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
              {{ studentStatusText }}
            </span>
            <span :class="priorityBadgeClass" class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
              {{ priorityText }}
            </span>
          </div>
        </div>
      </div>

      <!-- Pestañas -->
      <div class="border-b border-gray-200">
        <nav class="flex -mb-px">
          <button 
            v-for="tab in tabs" 
            :key="tab.name"
            @click="currentTab = tab.name"
            :class="[currentTab === tab.name ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm']"
          >
            {{ tab.label }}
          </button>
        </nav>
      </div>

      <!-- Contenido de pestañas -->
      <div class="p-6">
        <!-- Información Personal -->
        <div v-if="currentTab === 'personal'">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Columna izquierda -->
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h3>
              <dl class="space-y-4">
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Nombre completo</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ studentFullName }}</dd>
                </div>
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">RUT</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ studentRut }}</dd>
                </div>
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Fecha de nacimiento</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formattedBirthDate }}</dd>
                </div>
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Edad</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ studentAge }}</dd>
                </div>
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Tipo de necesidad</dt>
                  <dd class="mt-1">
                    <span :class="needTypeBadgeClass" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                      {{ needTypeText }}
                    </span>
                  </dd>
                </div>
              </dl>
            </div>

            <!-- Columna derecha -->
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Información Académica</h3>
              <dl class="space-y-4">
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Establecimiento</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ student.establishment?.name || 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Curso</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ studentCourse }}</dd>
                </div>
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Especialista asignado</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ assignedSpecialist }}</dd>
                </div>
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Fecha de evaluación</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formattedEvaluationDate }}</dd>
                </div>
              </dl>
            </div>
          </div>

          <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Diagnóstico y Observaciones</h3>
            <div class="bg-gray-50 p-4 rounded-md">
              <p class="text-sm text-gray-700">{{ student.diagnosis || 'Sin diagnóstico registrado' }}</p>
            </div>
            <div class="mt-4">
              <h4 class="text-sm font-medium text-gray-500">Observaciones iniciales</h4>
              <p class="text-sm text-gray-700 mt-1">{{ student.initial_observations || 'Sin observaciones' }}</p>
            </div>
          </div>
        </div>

        <!-- Contacto y Apoderados -->
        <div v-if="currentTab === 'contact'">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Información de contacto -->
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Contacto</h3>
              <dl class="space-y-4">
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Email</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ student.user?.email || 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ studentPhone }}</dd>
                </div>
              </dl>
            </div>

            <!-- Apoderados -->
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Apoderados</h3>
              <div v-if="guardians.length > 0" class="space-y-4">
                <div v-for="guardian in guardians" :key="guardian.id" class="border rounded-md p-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                      <span class="text-indigo-600 font-medium">
                        {{ getInitials(guardian.user?.name, guardian.user?.last_name) }}
                      </span>
                    </div>
                    <div class="ml-3">
                      <h4 class="text-sm font-medium text-gray-900">
                        {{ guardian.user?.name || 'N/A' }} {{ guardian.user?.last_name || '' }}
                      </h4>
                      <p class="text-sm text-gray-500">{{ guardian.relationship || 'N/A' }}</p>
                    </div>
                    <span v-if="guardian.is_primary" class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      Principal
                    </span>
                  </div>
                  <div class="mt-3">
                    <p class="text-sm text-gray-500">Email: {{ guardian.user?.email || 'N/A' }}</p>
                    <p class="text-sm text-gray-500">RUT: {{ guardian.user?.document?.rut || 'N/A' }}</p>
                  </div>
                </div>
              </div>
              <div v-else class="text-center py-6 text-gray-500">
                <p>No hay apoderados registrados</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Documentos -->
        <div v-if="currentTab === 'documents'">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Documentos</h3>
          <div v-if="documents.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div v-for="document in documents" :key="document.id" class="border rounded-md p-4 flex items-start">
              <div class="flex-shrink-0">
                <DocumentIcon class="h-8 w-8 text-indigo-500" />
              </div>
              <div class="ml-3">
                <h4 class="text-sm font-medium text-gray-900">{{ documentType(document.type) }}</h4>
                <p class="text-sm text-gray-500">{{ document.description || 'Sin descripción' }}</p>
                <p class="text-xs text-gray-400 mt-1">Subido el {{ formatDate(document.created_at) }}</p>
              </div>
              <div class="ml-auto">
                <a :href="document.path" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                  Ver
                </a>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-6 text-gray-500">
            <p>No hay documentos registrados</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Estado de carga -->
    <div v-else class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>

    <!-- Footer con botón de cerrar -->
    <template #footer>
      <div class="flex justify-end">
        <button 
          type="button"
          @click="closeModal"
          class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
        >
          Cerrar
        </button>
      </div>
    </template>
  </BaseModal>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import BaseModal from './BaseModal.vue';
import { DocumentIcon } from '@heroicons/vue/24/outline';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';

const props = defineProps({
  isOpen: Boolean,
  student: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['close']);

const currentTab = ref('personal');
const hasStudentData = ref(false);

// Observar cambios en el estudiante
watch(() => props.student, (newVal) => {
  hasStudentData.value = !!newVal;
}, { immediate: true });

const tabs = [
  { name: 'personal', label: 'Información Personal' },
  { name: 'contact', label: 'Contacto y Apoderados' },
  { name: 'documents', label: 'Documentos' }
];

// Título del modal
const modalTitle = computed(() => {
  return hasStudentData.value 
    ? `Perfil de ${studentFullName.value}` 
    : 'Cargando estudiante...';
});

// Computed properties con protección completa
const studentFullName = computed(() => {
  return props.student 
    ? `${props.student.user?.name || ''} ${props.student.user?.last_name || ''}`.trim() || 'Estudiante'
    : 'Estudiante';
});

const studentInitials = computed(() => {
  if (!props.student) return '?';
  const name = props.student.user?.name || '';
  const lastName = props.student.user?.last_name || '';
  return (name.charAt(0) + lastName.charAt(0)).toUpperCase();
});

const studentRut = computed(() => {
  return props.student?.user?.document?.rut || 'N/A';
});

const formattedBirthDate = computed(() => {
  if (!props.student || !props.student.birth_date) return 'N/A';
  return format(new Date(props.student.birth_date), 'dd/MM/yyyy', { locale: es });
});

const studentAge = computed(() => {
  if (!props.student || !props.student.birth_date) return 'N/A';
  const birthDate = new Date(props.student.birth_date);
  const today = new Date();
  let age = today.getFullYear() - birthDate.getFullYear();
  const m = today.getMonth() - birthDate.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  return `${age} años`;
});

const studentPhone = computed(() => {
  if (!props.student) return 'N/A';
  const phone = props.student.user?.phones?.find(p => p.primary);
  return phone ? `+${phone.country?.phone_code || ''} ${phone.phone_number || ''}`.trim() : 'N/A';
});

const needTypeText = computed(() => {
  return props.student?.need_type === 'permanent' ? 'Permanente' : 'Temporal';
});

const needTypeBadgeClass = computed(() => {
  return props.student?.need_type === 'permanent' 
    ? 'bg-purple-100 text-purple-800' 
    : 'bg-blue-100 text-blue-800';
});

const priorityText = computed(() => {
  if (!props.student) return 'N/A';
  switch (props.student.priority) {
    case 1: return 'Máxima';
    case 2: return 'Media';
    case 3: return 'Básica';
    default: return 'Sin definir';
  }
});

const priorityBadgeClass = computed(() => {
  if (!props.student) return 'bg-gray-100 text-gray-800';
  switch (props.student.priority) {
    case 1: return 'bg-red-100 text-red-800';
    case 2: return 'bg-yellow-100 text-yellow-800';
    case 3: return 'bg-green-100 text-green-800';
    default: return 'bg-gray-100 text-gray-800';
  }
});

const studentStatusText = computed(() => {
  return props.student?.active ? 'Activo' : 'Inactivo';
});

const statusBadgeClass = computed(() => {
  return props.student?.active 
    ? 'bg-green-100 text-green-800' 
    : 'bg-red-100 text-red-800';
});

const studentCourse = computed(() => {
  return props.student?.courses?.[0]?.name || 'N/A';
});

const assignedSpecialist = computed(() => {
  if (!props.student?.assigned_specialist) return 'N/A';
  
  const specialist = props.student.assigned_specialist;
  const name = specialist.user?.name || '';
  const lastName = specialist.user?.last_name || '';
  const specialty = specialist.specialty?.name || '';
  
  return `${name} ${lastName}${specialty ? ` (${specialty})` : ''}`;
});

const formattedEvaluationDate = computed(() => {
  if (!props.student || !props.student.evaluation_date) return 'N/A';
  return format(new Date(props.student.evaluation_date), 'dd/MM/yyyy', { locale: es });
});

const guardians = computed(() => {
  return props.student?.guardians || [];
});

const documents = computed(() => {
  return props.student?.documents || [];
});

const getInitials = (name, lastName) => {
  const firstInitial = name ? name.charAt(0).toUpperCase() : '';
  const lastInitial = lastName ? lastName.charAt(0).toUpperCase() : '';
  return firstInitial + lastInitial;
};

const documentType = (type) => {
  const types = {
    'medical_report': 'Informe Médico',
    'previous_report': 'Reporte Anterior',
    'evaluation': 'Evaluación',
    'consent': 'Consentimiento'
  };
  return types[type] || type;
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return format(new Date(dateString), 'dd/MM/yyyy', { locale: es });
};

const closeModal = () => {
  emit('close');
};
</script>
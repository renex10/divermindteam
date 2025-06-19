<template>
  <!-- ruta del archivo es resources\js\Components\Modal\FormModal.vue -->
  <Transition name="modal-fade">
    <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fondo del modal -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-900 bg-opacity-75"></div>
        </div>

        <!-- Contenido del modal -->
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
          <!-- Header -->
          <div class="bg-indigo-700 px-6 py-4 sm:px-6 rounded-t-lg">
            <div class="flex items-center justify-between">
              <h3 class="text-xl font-semibold text-white">Nuevo Estudiante PIE</h3>
              <button @click="closeModal" class="text-indigo-200 hover:text-white focus:outline-none">
                <XMarkIcon class="h-6 w-6" />
              </button>
            </div>
          </div>

          <!-- Wizard Steps -->
          <div class="px-6 pt-6">
            <div class="wizard-steps">
              <div v-for="(step, index) in steps" :key="index" class="wizard-step">
                <div class="step-indicator">
                  <div :class="[
                    'step-number',
                    currentStep === index ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-gray-300',
                    index < currentStep ? 'bg-green-500 border-green-500' : ''
                  ]">
                    <CheckIcon v-if="index < currentStep" class="h-4 w-4" />
                    <span v-else>{{ index + 1 }}</span>
                  </div>
                  <div class="step-label">{{ step.label }}</div>
                </div>
                <div v-if="index < steps.length - 1" class="step-connector"></div>
              </div>
            </div>
          </div>

          <!-- Form Content -->
          <div class="px-6 py-4">
            <form @submit.prevent="handleSubmit" enctype="multipart/form-data" class="space-y-6">
              <!-- Paso 1: Datos Básicos -->
              <div v-show="currentStep === 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormKit type="text" v-model="formData.full_name" name="full_name" label="Nombres*"
                  placeholder="Ingrese nombres" validation="required" outer-class="mb-0" label-class="formkit-label"
                  input-class="formkit-input" help-class="formkit-help" />

                <FormKit type="text" v-model="formData.last_name" name="last_name" label="Apellidos *"
                  placeholder="Apellidos" validation="required" outer-class="mb-0" label-class="formkit-label"
                  input-class="formkit-input" help-class="formkit-help" />

                <FormKit type="text" v-model="formData.rut" name="rut" label="RUT *" placeholder="12.345.678-5"
                  validation="required" outer-class="mb-0" label-class="formkit-label" input-class="formkit-input" />

                <FormKit type="date" v-model="formData.birth_date" name="birth_date" label="Fecha de Nacimiento *"
                  validation="required" outer-class="mb-0" label-class="formkit-label" input-class="formkit-input" />

                <FormKit type="select" v-model="formData.course_id" name="course_id" label="Curso *"
                  :options="courseOptions" placeholder="Seleccione curso" validation="required" outer-class="mb-0"
                  label-class="formkit-label" input-class="formkit-input" />

                <FormKit type="text" v-model="formData.diagnosis" name="diagnosis" label="Diagnóstico Preliminar"
                  placeholder="Ej: TEL, TDAH, DEA..." outer-class="mb-0" label-class="formkit-label"
                  input-class="formkit-input" />

                <FormKit type="email" v-model="formData.guardian_email" name="guardian_email"
                  label="Contacto de Apoderado" placeholder="correo@ejemplo.com" validation="email" outer-class="mb-0"
                  label-class="formkit-label" input-class="formkit-input" />
              </div>

              <!-- Paso 2: Documentos -->
              <div v-show="currentStep === 1" class="space-y-6">
                <div>
                  <label class="formkit-label">Informe Médico *</label>
                  <input type="file" ref="medicalReportRef" name="medical_report" accept=".pdf,.doc,.docx,.jpg,.png"
                    @change="handleMedicalReportChange" class="formkit-file-input" />
                  <p class="formkit-help">Formatos aceptados: PDF, Word, JPG, PNG (Máx. 5MB por archivo)</p>
                </div>

                <div>
                  <label class="formkit-label">Informes Previos</label>
                  <input type="file" ref="previousReportsRef" name="previous_reports[]" accept=".pdf,.doc,.docx"
                    multiple @change="handlePreviousReportsChange" class="formkit-file-input" />
                  <p class="formkit-help">Formatos aceptados: PDF, Word (Máx. 5MB por archivo)</p>
                </div>
              </div>

              <!-- Paso 3: Clasificación -->
              <div v-show="currentStep === 2" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormKit type="select" v-model="formData.need_type" name="need_type" label="Tipo de Necesidad *"
                  :options="[
                    { value: 'permanent', label: 'Permanente' },
                    { value: 'temporary', label: 'Transitoria' }
                  ]" validation="required" outer-class="mb-0" label-class="formkit-label" input-class="formkit-input" />
                <FormKit type="select" v-model="selectedPriority" name="priority" label="Prioridad *"
                  :options="priorityOptions" validation="required" outer-class="mb-0" label-class="formkit-label"
                  input-class="formkit-input" />

                <div class="md:col-span-2">
                  <FormKit type="textarea" v-model="formData.special_needs" name="special_needs"
                    label="Necesidades Específicas" placeholder="Describa las necesidades específicas del estudiante"
                    rows="4" outer-class="mb-0" label-class="formkit-label" input-class="formkit-textarea" />
                </div>
              </div>

              <!-- Paso 4: Consentimientos -->
              <div v-show="currentStep === 3" class="space-y-6">
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                  <FormKit type="checkbox" v-model="formData.consent_pie" name="consent_pie"
                    label="Consentimiento para participación en PIE"
                    help="El apoderado autoriza la participación del estudiante en el programa" outer-class="mb-0"
                    label-class="font-medium text-gray-700" />
                </div>

                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                  <FormKit type="checkbox" v-model="formData.data_processing" name="data_processing"
                    label="Autorización de tratamiento de datos"
                    help="Autoriza el uso de información para fines educativos" outer-class="mb-0"
                    label-class="font-medium text-gray-700" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <FormKit type="text" v-model="formData.guardian_name" name="guardian_name"
                    label="Nombre del Apoderado *" validation="required_if:consent_pie,true" outer-class="mb-0"
                    label-class="formkit-label" input-class="formkit-input" />

                  <FormKit type="text" v-model="formData.guardian_rut" name="guardian_rut" label="RUT del Apoderado *"
                    validation="required_if:consent_pie,true" outer-class="mb-0" label-class="formkit-label"
                    input-class="formkit-input" />
                </div>
              </div>

              <!-- Paso 5: Asignación -->
              <div v-show="currentStep === 4" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormKit type="select" v-model="selectedSpecialist" name="assigned_specialist"
                  label="Profesional Asignado *" :options="specialistOptions" validation="required" outer-class="mb-0"
                  label-class="formkit-label" input-class="formkit-input" />

                <FormKit type="date" v-model="formData.evaluation_date" name="evaluation_date"
                  label="Fecha de Evaluación Inicial *" validation="required" outer-class="mb-0"
                  label-class="formkit-label" input-class="formkit-input" />

                <div class="md:col-span-2">
                  <FormKit type="textarea" v-model="formData.initial_observations" name="initial_observations"
                    label="Observaciones Iniciales"
                    placeholder="Describa las observaciones relevantes para el profesional asignado" rows="4"
                    outer-class="mb-0" label-class="formkit-label" input-class="formkit-textarea" />
                </div>
              </div>

              <!-- Próximos pasos (solo visible en primer paso) -->
              <div v-if="currentStep === 0" class="p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                <h4 class="flex items-center text-lg font-medium text-indigo-800 mb-3">
                  <InformationCircleIcon class="h-5 w-5 mr-2" />
                  Próximos pasos
                </h4>
                <ul class="text-indigo-700 text-sm space-y-2">
                  <li class="flex items-start">
                    <CheckCircleIcon class="h-4 w-4 mt-0.5 mr-2 text-indigo-500 flex-shrink-0" />
                    <span>Subir documentos de respaldo (diagnóstico médico, informes)</span>
                  </li>
                  <li class="flex items-start">
                    <CheckCircleIcon class="h-4 w-4 mt-0.5 mr-2 text-indigo-500 flex-shrink-0" />
                    <span>Clasificar tipo de NEE (Permanente/Transitoria)</span>
                  </li>
                  <li class="flex items-start">
                    <CheckCircleIcon class="h-4 w-4 mt-0.5 mr-2 text-indigo-500 flex-shrink-0" />
                    <span>Obtener consentimiento de apoderados</span>
                  </li>
                  <li class="flex items-start">
                    <CheckCircleIcon class="h-4 w-4 mt-0.5 mr-2 text-indigo-500 flex-shrink-0" />
                    <span>Asignar profesional PIE y crear PAI inicial</span>
                  </li>
                </ul>
              </div>

              <!-- Controles de navegación -->
              <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <button v-if="currentStep > 0" type="button" @click="prevStep" class="btn-secondary">
                  <ArrowLeftIcon class="h-4 w-4 mr-1" />
                  Anterior
                </button>
                <div v-else></div>

                <button v-if="currentStep < steps.length - 1" type="button" @click="nextStep" class="btn-primary">
                  Siguiente
                  <ArrowRightIcon class="h-4 w-4 ml-1" />
                </button>
                <button v-else type="submit" class="btn-primary">
                  Guardar Estudiante
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import axios from 'axios';
import {
  XMarkIcon,
  CheckIcon,
  InformationCircleIcon,
  CheckCircleIcon,
  ArrowLeftIcon,
  ArrowRightIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
  isOpen: Boolean,
  specialists: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'submit']);

const currentStep = ref(0);

// Cambiar a selectedSpecialist y selectedPriority
const selectedSpecialist = ref(null);
const selectedPriority = ref(null);

const formData = reactive({
  full_name: '',
  last_name: '',
  rut: '',
  birth_date: '',
  course_id: '',
  diagnosis: '',
  guardian_email: '',
  medical_report: null,
  previous_reports: [],
  need_type: null,
  priority: null,
  special_needs: '',
  consent_pie: false,
  data_processing: false,
  guardian_name: '',
  guardian_rut: '',
  assigned_specialist: null,
  evaluation_date: '',
  initial_observations: ''
});

// Referencias para los inputs de archivo
const medicalReportRef = ref(null);
const previousReportsRef = ref(null);

const steps = [
  { label: 'Datos Básicos' },
  { label: 'Documentos' },
  { label: 'Clasificación' },
  { label: 'Consentimientos' },
  { label: 'Asignación' }
];

const courseOptions = {
  1: '1° Básico A',
  2: '1° Básico B',
  3: '2° Básico A',
  4: '2° Básico B',
  5: '3° Básico A',
  6: '3° Básico B'
};

// Usar array de objetos para prioridad
const priorityOptions = ref([
  { value: 1, label: 'Máxima' },
  { value: 2, label: 'Media' },
  { value: 3, label: 'Básica' }
]);

// Usar array de objetos para especialistas
const specialistOptions = ref([
  { value: 1, label: 'Especialista en Lenguaje' },
  { value: 2, label: 'Psicólogo Educacional' },
  { value: 3, label: 'Terapeuta Ocupacional' },
  { value: 4, label: 'Educador Diferencial' }
]);

// Cargar profesionales reales desde API
onMounted(async () => {
  try {
    const response = await axios.get('/api/professionals');
    specialistOptions.value = response.data.map(prof => ({
      value: prof.id,
      label: `${prof.user.name} - ${prof.specialty.name}`
    }));
    console.log('Profesionales cargados:', specialistOptions.value);
  } catch (error) {
    console.error('Error cargando profesionales', error);
    // Mantener opciones por defecto si falla la API
    specialistOptions.value = [
      { value: 1, label: 'Especialista en Lenguaje' },
      { value: 2, label: 'Psicólogo Educacional' },
      { value: 3, label: 'Terapeuta Ocupacional' },
      { value: 4, label: 'Educador Diferencial' }
    ];
  }
});

// Actualizar formData cuando cambian las selecciones
watch(selectedSpecialist, (newValue) => {
  formData.assigned_specialist = newValue;
});

watch(selectedPriority, (newValue) => {
  formData.priority = newValue;
});

// Manejar cambios en archivos
const handleMedicalReportChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    formData.medical_report = file;
    console.log('Medical report file selected:', file.name);
  }
};

const handlePreviousReportsChange = (event) => {
  const files = Array.from(event.target.files);
  if (files.length > 0) {
    formData.previous_reports = files;
    console.log('Previous reports files selected:', files.map(f => f.name));
  }
};

const nextStep = () => {
  if (currentStep.value < steps.length - 1) {
    currentStep.value++;
  }
};

const prevStep = () => {
  if (currentStep.value > 0) {
    currentStep.value--;
  }
};

const closeModal = () => {
  // Resetear formulario
  Object.keys(formData).forEach(key => {
    if (key === 'consent_pie' || key === 'data_processing') {
      formData[key] = false;
    } else if (key === 'priority' || key === 'assigned_specialist') {
      formData[key] = null;
    } else {
      formData[key] = '';
    }
  });

  // Resetear selecciones
  selectedSpecialist.value = null;
  selectedPriority.value = null;

  currentStep.value = 0;

  // Limpiar inputs de archivo
  if (medicalReportRef.value) medicalReportRef.value.value = '';
  if (previousReportsRef.value) previousReportsRef.value.value = '';

  emit('close');
};

const handleSubmit = () => {
  // Crear copia de los datos para enviar
  const payload = { ...formData };

  // Convertir tipos específicos
  payload.priority = parseInt(payload.priority);
  payload.assigned_specialist = parseInt(payload.assigned_specialist);
  payload.course_id = parseInt(payload.course_id);

  console.log('Datos enviados:', payload);

  emit('submit', payload);
  closeModal();
};
</script>

<style>
/* Animaciones */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

/* Wizard Steps */
.wizard-steps {
  @apply flex justify-between items-center mb-8;
}

.wizard-step {
  @apply flex-1 flex items-center;
}

.step-indicator {
  @apply flex flex-col items-center relative;
}

.step-number {
  @apply rounded-full h-8 w-8 flex items-center justify-center border-2 mb-2 z-10 transition-colors duration-300;
}

.step-label {
  @apply text-xs text-center font-medium text-gray-600;
}

.step-connector {
  @apply flex-1 h-0.5 bg-gray-200 mx-2;
}

/* Formulario personalizado */
.formkit-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

.formkit-input {
  @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border;
}

.formkit-textarea {
  @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border;
}

.formkit-file-input {
  @apply block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100;
}

.formkit-help {
  @apply mt-1 text-xs text-gray-500;
}

/* Botones */
.btn-primary {
  @apply inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors;
}

.btn-secondary {
  @apply inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors;
}

/* Validación */
.formkit-messages {
  @apply mt-1 text-sm text-red-600;
}

.formkit-message {
  @apply block;
}
</style>
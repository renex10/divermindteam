<template>
  <Transition name="modal-fade">
    <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fondo del modal -->
        <Transition name="modal-fade">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-75"></div>
          </div>
        </Transition>

        <!-- Contenido del modal -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
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
                  <div 
                    :class="[
                      'step-number',
                      currentStep === index ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-gray-300',
                      index < currentStep ? 'bg-green-500 border-green-500' : ''
                    ]"
                  >
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
            <FormKit
              type="form"
              :actions="false"
              @submit="handleSubmit"
              form-class="space-y-6"
            >
              <!-- Paso 1: Datos Básicos -->
              <div v-show="currentStep === 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormKit
                  type="text"
                  name="full_name"
                  label="Nombre Completo *"
                  placeholder="Ingrese nombre completo"
                  validation="required"
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-input"
                  help-class="formkit-help"
                />
                
                <FormKit
                  type="text"
                  name="rut"
                  label="RUT *"
                  placeholder="12.345.678-9"
                  validation="required|matches:/^\d{1,2}\.\d{3}\.\d{3}-[\dkK]$/"
                  :validation-messages="{
                    matches: 'Formato de RUT inválido (ej: 12.345.678-9)'
                  }"
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-input"
                />
                
                <FormKit
                  type="date"
                  name="birth_date"
                  label="Fecha de Nacimiento *"
                  validation="required"
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-input"
                />
                
                <FormKit
                  type="select"
                  name="course"
                  label="Curso *"
                  :options="courseOptions"
                  placeholder="Seleccione curso"
                  validation="required"
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-input"
                />
                
                <FormKit
                  type="text"
                  name="diagnosis"
                  label="Diagnóstico Preliminar"
                  placeholder="Ej: TEL, TDAH, DEA..."
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-input"
                />
                
                <FormKit
                  type="email"
                  name="guardian_email"
                  label="Contacto de Apoderado"
                  placeholder="correo@ejemplo.com"
                  validation="email"
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-input"
                />
              </div>

              <!-- Paso 2: Documentos -->
              <div v-show="currentStep === 1" class="space-y-6">
                <FormKit
                  type="file"
                  name="medical_report"
                  label="Informe Médico *"
                  accept=".pdf,.doc,.docx,.jpg,.png"
                  multiple
                  help="Formatos aceptados: PDF, Word, JPG, PNG (Máx. 5MB por archivo)"
                  validation="required"
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-file-input"
                />
                
                <FormKit
                  type="file"
                  name="previous_reports"
                  label="Informes Previos"
                  accept=".pdf,.doc,.docx"
                  multiple
                  help="Formatos aceptados: PDF, Word (Máx. 5MB por archivo)"
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-file-input"
                />
              </div>

              <!-- Paso 3: Clasificación -->
              <div v-show="currentStep === 2" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormKit
                  type="select"
                  name="need_type"
                  label="Tipo de Necesidad *"
                  :options="{
                    permanent: 'Permanente',
                    temporary: 'Transitoria'
                  }"
                  validation="required"
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-input"
                />
                
                <FormKit
                  type="select"
                  name="priority"
                  label="Prioridad *"
                  :options="{
                    1: 'Máxima',
                    2: 'Media',
                    3: 'Básica'
                  }"
                  validation="required"
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-input"
                />
                
                <div class="md:col-span-2">
                  <FormKit
                    type="textarea"
                    name="special_needs"
                    label="Necesidades Específicas"
                    placeholder="Describa las necesidades específicas del estudiante"
                    rows="4"
                    outer-class="mb-0"
                    label-class="formkit-label"
                    input-class="formkit-textarea"
                  />
                </div>
              </div>

              <!-- Paso 4: Consentimientos -->
              <div v-show="currentStep === 3" class="space-y-6">
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                  <FormKit
                    type="checkbox"
                    name="consent_pie"
                    label="Consentimiento para participación en PIE"
                    help="El apoderado autoriza la participación del estudiante en el programa"
                    outer-class="mb-0"
                    label-class="font-medium text-gray-700"
                  />
                </div>
                
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                  <FormKit
                    type="checkbox"
                    name="data_processing"
                    label="Autorización de tratamiento de datos"
                    help="Autoriza el uso de información para fines educativos"
                    outer-class="mb-0"
                    label-class="font-medium text-gray-700"
                  />
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <FormKit
                    type="text"
                    name="guardian_name"
                    label="Nombre del Apoderado *"
                    validation="required_if:consent_pie"
                    outer-class="mb-0"
                    label-class="formkit-label"
                    input-class="formkit-input"
                  />
                  
                  <FormKit
                    type="text"
                    name="guardian_rut"
                    label="RUT del Apoderado *"
                    validation="required_if:consent_pie|matches:/^\d{1,2}\.\d{3}\.\d{3}-[\dkK]$/"
                    outer-class="mb-0"
                    label-class="formkit-label"
                    input-class="formkit-input"
                  />
                </div>
              </div>

              <!-- Paso 5: Asignación -->
              <div v-show="currentStep === 4" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <FormKit
                  type="select"
                  name="assigned_specialist"
                  label="Profesional Asignado *"
                  :options="specialistOptions"
                  validation="required"
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-input"
                />
                
                <FormKit
                  type="date"
                  name="evaluation_date"
                  label="Fecha de Evaluación Inicial *"
                  validation="required"
                  outer-class="mb-0"
                  label-class="formkit-label"
                  input-class="formkit-input"
                />
                
                <div class="md:col-span-2">
                  <FormKit
                    type="textarea"
                    name="initial_observations"
                    label="Observaciones Iniciales"
                    placeholder="Describa las observaciones relevantes para el profesional asignado"
                    rows="4"
                    outer-class="mb-0"
                    label-class="formkit-label"
                    input-class="formkit-textarea"
                  />
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
                <button 
                  v-if="currentStep > 0"
                  type="button"
                  @click="prevStep"
                  class="btn-secondary"
                >
                  <ArrowLeftIcon class="h-4 w-4 mr-1" />
                  Anterior
                </button>
                <div v-else></div>
                
                <button 
                  v-if="currentStep < steps.length - 1"
                  type="button"
                  @click="nextStep"
                  class="btn-primary"
                >
                  Siguiente
                  <ArrowRightIcon class="h-4 w-4 ml-1" />
                </button>
                <FormKit
                  v-else
                  type="submit"
                  label="Guardar Estudiante"
                  input-class="btn-primary"
                />
              </div>
            </FormKit>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref } from 'vue';
import { 
  XMarkIcon, 
  CheckIcon, 
  InformationCircleIcon,
  CheckCircleIcon,
  ArrowLeftIcon,
  ArrowRightIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
  isOpen: Boolean
});

const emit = defineEmits(['close', 'submit']);

const currentStep = ref(0);
const steps = [
  { label: 'Datos Básicos' },
  { label: 'Documentos' },
  { label: 'Clasificación' },
  { label: 'Consentimientos' },
  { label: 'Asignación' }
];

const courseOptions = {
  '1A': '1° Básico A',
  '1B': '1° Básico B',
  '2A': '2° Básico A',
  '2B': '2° Básico B',
  '3A': '3° Básico A',
  '3B': '3° Básico B'
};

const specialistOptions = {
  'sp1': 'Especialista en Lenguaje',
  'sp2': 'Psicólogo Educacional',
  'sp3': 'Terapeuta Ocupacional',
  'sp4': 'Educador Diferencial'
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
  emit('close');
};

const handleSubmit = (formData) => {
  emit('submit', formData);
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
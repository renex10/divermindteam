<!-- resources/js/Components/Modal/StudentFormModal.vue -->
<template>
  <BaseModal :isOpen="isOpen" title="Nuevo Estudiante PIE" @close="closeModal">
    <Wizard
      :steps="steps"
      :formData="formData"
      :externalData="externalData"
      submit-label="Guardar Estudiante"
      @update:formData="updateFormData"
      @submit="handleSubmit"
    />
  </BaseModal>
</template>

<script setup>
import { ref, reactive, defineProps, defineEmits, computed } from 'vue';
import BaseModal from '@/Components/Modal/BaseModal.vue';
import Wizard from '@/Components/Wizard/Wizard.vue';

// Importar componentes de pasos
import BasicDataStep from '@/Components/StudentForm/BasicDataStep.vue';
import DocumentsStep from '@/Components/StudentForm/DocumentsStep.vue';
import ClassificationStep from '@/Components/StudentForm/ClassificationStep.vue';
import ConsentsStep from '@/Components/StudentForm/ConsentsStep.vue';
import AssignmentStep from '@/Components/StudentForm/AssignmentStep.vue';

const props = defineProps({
  isOpen: Boolean,
  initialData: {
    type: Object,
    default: () => ({})
  },
  externalData: {
    type: Object,
    default: () => ({
      courses: [],
      specialists: []
    })
  }
});

const emit = defineEmits(['close', 'submit']);

// Configuración de pasos
const steps = ref([
  { label: 'Datos Básicos', component: BasicDataStep },
  { label: 'Documentos', component: DocumentsStep },
  { label: 'Clasificación', component: ClassificationStep },
  { label: 'Consentimientos', component: ConsentsStep },
  { label: 'Asignación', component: AssignmentStep }
]);

// Datos del formulario
const formData = reactive({
  ...props.initialData,
  medical_report: null,
  previous_reports: [],
  consent_pie: false,
  data_processing: false
});

// Actualizar datos del formulario
const updateFormData = (newData) => {
  Object.assign(formData, newData);
};

// Cerrar modal
const closeModal = () => {
  emit('close');
};

// Enviar formulario
const handleSubmit = () => {
  emit('submit', formData);
  closeModal();
};
</script>
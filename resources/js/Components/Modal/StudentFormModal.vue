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
import { ref, reactive, defineProps, defineEmits, watch } from 'vue';
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

// Función para crear la estructura de datos por defecto
const createDefaultFormData = (initialData = {}) => {
  console.log('createDefaultFormData recibió:', initialData);
  
  return {
    // Datos del estudiante
    student_rut: initialData.student_rut || initialData.rut || '',
    birth_date: initialData.birth_date || initialData.date_of_birth || '',
    course_id: initialData.course_id || initialData.course?.id || null,
    diagnosis: initialData.diagnosis || initialData.preliminary_diagnosis || '',
    guardian_email: initialData.guardian_email || initialData.guardian?.email || initialData.user?.email || '',
    
    // Datos del nuevo usuario (apoderado o tutor)
    new_user: {
      name: initialData.new_user?.name || 
            initialData.guardian_name || 
            initialData.guardian?.name || 
            initialData.user?.name || '',
      last_name: initialData.new_user?.last_name || 
                 initialData.guardian_last_name || 
                 initialData.guardian?.last_name || 
                 initialData.user?.last_name || '',
      email: initialData.new_user?.email || 
             initialData.guardian_email || 
             initialData.guardian?.email || 
             initialData.user?.email || '',
      password: initialData.new_user?.password || '' // Siempre vacía para edición
    },
    
    // Datos adicionales del formulario
    medical_report: initialData.medical_report || null,
    previous_reports: initialData.previous_reports || [],
    consent_pie: initialData.consent_pie || false,
    data_processing: initialData.data_processing || false,
    
    // Incluir cualquier otro dato que venga en initialData
    ...initialData
  };
};

// Datos del formulario con inicialización correcta
const formData = reactive(createDefaultFormData(props.initialData));

// Actualizar formData si cambian los datos iniciales
watch(() => props.initialData, (newVal) => {
  console.log('Watch triggered con nuevos initialData:', newVal);
  
  // Solo actualizar si realmente hay nuevos datos
  if (newVal && Object.keys(newVal).length > 0) {
    const newFormData = createDefaultFormData(newVal);
    console.log('Nuevos formData generados:', newFormData);
    
    // Actualizar todos los campos del formData
    Object.keys(newFormData).forEach(key => {
      if (key === 'new_user') {
        // Manejar el objeto anidado new_user
        Object.assign(formData.new_user, newFormData.new_user);
      } else {
        formData[key] = newFormData[key];
      }
    });
  }
}, { deep: true, immediate: true });

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
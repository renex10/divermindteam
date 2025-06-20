<!-- resources/js/Components/StudentForm/BasicDataStep.vue -->
<template>
  <div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <FormKit
        type="text"
        v-model="formData.full_name"
        name="full_name"
        label="Nombres*"
        placeholder="Ingrese nombres"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
        help-class="formkit-help"
      />

      <FormKit
        type="text"
        v-model="formData.last_name"
        name="last_name"
        label="Apellidos *"
        placeholder="Apellidos"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
        help-class="formkit-help"
      />

      <FormKit
        type="text"
        v-model="formData.rut"
        name="rut"
        label="RUT *"
        placeholder="12.345.678-5"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="date"
        v-model="formData.birth_date"
        name="birth_date"
        label="Fecha de Nacimiento *"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="select"
        v-model="formData.course_id"
        name="course_id"
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
        v-model="formData.diagnosis"
        name="diagnosis"
        label="Diagnóstico Preliminar"
        placeholder="Ej: TEL, TDAH, DEA..."
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="email"
        v-model="formData.guardian_email"
        name="guardian_email"
        label="Contacto de Apoderado"
        placeholder="correo@ejemplo.com"
        validation="email"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />
    </div>

    <!-- Próximos pasos (solo visible en este paso) -->
    <div v-if="showNextSteps" class="p-4 bg-indigo-50 rounded-lg border border-indigo-100 mt-6">
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
  </div>
</template>

<script setup>
import { InformationCircleIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';
import { computed, onMounted } from 'vue';

const props = defineProps({
  formData: Object,
  externalData: Object,
  showNextSteps: Boolean
});

// Verificar la llegada de props
console.log("BasicDataStep - Props recibidas:", {
  formData: props.formData,
  externalData: props.externalData,
  showNextSteps: props.showNextSteps
});

// Verificar específicamente los cursos
console.log("Cursos recibidos en externalData:", 
  props.externalData?.courses ? props.externalData.courses : "UNDEFINED");

// Transformar cursos
const courseOptions = computed(() => {
  console.log("Transformando cursos...");
  
  if (!props.externalData?.courses) {
    console.warn("No se encontró 'courses' en externalData");
    return [];
  }
  
  if (!Array.isArray(props.externalData.courses)) {
    console.error("'courses' no es un array:", typeof props.externalData.courses);
    return [];
  }
  
  const options = props.externalData.courses.map(course => {
    if (!course.id || !course.name) {
      console.warn("Curso con formato inválido:", course);
      return null;
    }
    return {
      value: course.id,
      label: course.name
    };
  }).filter(Boolean);
  
  console.log("Opciones transformadas:", options);
  return options;
});

// Verificar cuando el componente se monta
onMounted(() => {
  console.log("BasicDataStep montado");
});
</script>
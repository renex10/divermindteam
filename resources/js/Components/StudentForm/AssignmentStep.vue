<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- 
      =====================================================
      CAMPO: PROFESIONAL ASIGNADO
      =====================================================
      Este campo permite seleccionar un especialista activo
      para asignarlo al estudiante.
      Las opciones vienen de externalData.specialists
    -->
    <FormKit
      type="select"
      v-model="formData.assigned_specialist"
      name="assigned_specialist"
      label="Profesional Asignado *"
      :options="specialistOptions"
      validation="required"
      outer-class="mb-0"
      label-class="formkit-label"
      input-class="formkit-input"
    />

    <!-- 
      =====================================================
      CAMPO: FECHA DE EVALUACIÓN
      =====================================================
      Fecha inicial para la evaluación del estudiante
      por parte del especialista asignado.
    -->
    <FormKit
      type="date"
      v-model="formData.evaluation_date"
      name="evaluation_date"
      label="Fecha de Evaluación Inicial *"
      validation="required"
      outer-class="mb-0"
      label-class="formkit-label"
      input-class="formkit-input"
    />

    <!-- 
      =====================================================
      CAMPO: OBSERVACIONES INICIALES
      =====================================================
      Notas relevantes para el profesional asignado.
      Ocupa el ancho completo en dispositivos medianos y grandes.
    -->
    <div class="md:col-span-2">
      <FormKit
        type="textarea"
        v-model="formData.initial_observations"
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
</template>

<script setup>
import { computed, onMounted } from 'vue';

const props = defineProps({
  formData: {
    type: Object,
    required: true
  },
  externalData: {
    type: Object,
    default: () => ({})
  }
});

// Inicializar valores por defecto para evitar undefined y para que se reflejen en inputs
onMounted(() => {
  if (props.formData.assigned_specialist === undefined) {
    props.formData.assigned_specialist = null;
  }
  if (props.formData.evaluation_date === undefined) {
    props.formData.evaluation_date = '';
  }
  if (props.formData.initial_observations === undefined) {
    props.formData.initial_observations = '';
  }
});

const specialistOptions = computed(() => {
  if (!props.externalData?.specialists) {
    console.error("No se recibieron especialistas");
    return [];
  }
  
  if (!Array.isArray(props.externalData.specialists)) {
    console.error("'specialists' debe ser un array", props.externalData.specialists);
    return [];
  }
  
  return props.externalData.specialists.map(specialist => ({
    value: specialist.id,
    label: `${specialist.name} ${specialist.last_name}`
  }));
});
</script>

<style scoped>
.formkit-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

.formkit-input, .formkit-textarea {
  @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500;
}

.formkit-input {
  @apply py-2 px-3 border sm:text-sm;
}

.formkit-textarea {
  @apply py-2 px-3 border sm:text-sm;
}
</style>

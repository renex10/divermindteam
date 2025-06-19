<!-- resources/js/Components/StudentForm/AssignmentStep.vue -->
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <FormKit
      type="select"
      v-model="formData.assigned_specialist"
      name="assigned_specialist"
      label="Profesional Asignado *"
      :options="externalData.specialists"
      validation="required"
      outer-class="mb-0"
      label-class="formkit-label"
      input-class="formkit-input"
    />

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
defineProps({
  formData: {
    type: Object,
    required: true
  },
  externalData: {
    type: Object,
    default: () => ({})
  }
});
</script>
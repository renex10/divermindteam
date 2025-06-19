<!-- resources/js/Components/StudentForm/ClassificationStep.vue -->
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <FormKit
      type="select"
      v-model="formData.need_type"
      name="need_type"
      label="Tipo de Necesidad *"
      :options="[
        { value: 'permanent', label: 'Permanente' },
        { value: 'temporary', label: 'Transitoria' }
      ]"
      validation="required"
      outer-class="mb-0"
      label-class="formkit-label"
      input-class="formkit-input"
    />
    <FormKit
      type="select"
      v-model="formData.priority"
      name="priority"
      label="Prioridad *"
      :options="priorityOptions"
      validation="required"
      outer-class="mb-0"
      label-class="formkit-label"
      input-class="formkit-input"
    />

    <div class="md:col-span-2">
      <FormKit
        type="textarea"
        v-model="formData.special_needs"
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
</template>

<script setup>
defineProps({
  formData: {
    type: Object,
    required: true
  }
});

// Las opciones de prioridad podrían venir de externalData
const priorityOptions = [
  { value: 1, label: 'Máxima' },
  { value: 2, label: 'Media' },
  { value: 3, label: 'Básica' }
];
</script>
<template>
  <div class="space-y-6">
    <!-- Sección de Consentimiento PIE -->
    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
      <FormKit
        type="checkbox"
        v-model="formData.consent_pie"
        name="consent_pie"
        label="Consentimiento para participación en PIE"
        help="El apoderado autoriza la participación del estudiante en el programa"
        outer-class="mb-0"
        label-class="font-medium text-gray-700"
      />
    </div>

    <!-- Sección de Autorización de Datos -->
    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
      <FormKit
        type="checkbox"
        v-model="formData.data_processing"
        name="data_processing"
        label="Autorización de tratamiento de datos"
        help="Autoriza el uso de información para fines educativos"
        outer-class="mb-0"
        label-class="font-medium text-gray-700"
      />
    </div>

    <!-- Sección de Datos del Apoderado (condicional) -->
    <div v-if="formData.consent_pie" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <FormKit
        type="text"
        v-model="formData.guardian_name"
        name="guardian_name"
        label="Nombre del Apoderado *"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="text"
        v-model="formData.guardian_rut"
        name="guardian_rut"
        label="RUT del Apoderado *"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />
      
      <!-- Campo de Parentesco actualizado -->
      <div class="md:col-span-2">
        <FormKit
          type="select"
          v-model="formData.relationship"
          name="relationship"
          label="Parentesco con el estudiante *"
          :options="[
            { value: 'parent', label: 'Padre/Madre' },
            { value: 'tutor', label: 'Tutor Legal' },
            { value: 'other', label: 'Otro' }
          ]"
          outer-class="mb-0"
          label-class="formkit-label"
          input-class="formkit-input"
        />
      </div>
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
</script>

<style scoped>
.formkit-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

.formkit-input {
  @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border;
}
</style>


<!-- resources/js/Components/StudentForm/ConsentsStep.vue -->
<template>
  <div class="space-y-6">
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <FormKit
        type="text"
        v-model="formData.guardian_name"
        name="guardian_name"
        label="Nombre del Apoderado *"
        :validation="formData.consent_pie ? 'required' : ''"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="text"
        v-model="formData.guardian_rut"
        name="guardian_rut"
        label="RUT del Apoderado *"
        :validation="formData.consent_pie ? 'required' : ''"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
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
</script>
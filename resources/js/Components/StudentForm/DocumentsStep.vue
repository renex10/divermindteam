<!-- resources/js/Components/StudentForm/DocumentsStep.vue -->
<template>
  <div class="space-y-6">
    <div>
      <label class="formkit-label">Informe Médico *</label>
      <input
        type="file"
        name="medical_report"
        accept=".pdf,.doc,.docx,.jpg,.png"
        @change="handleMedicalReportChange"
        class="formkit-file-input"
      />
      <p class="formkit-help">Formatos aceptados: PDF, Word, JPG, PNG (Máx. 5MB por archivo)</p>
    </div>

    <div>
      <label class="formkit-label">Informes Previos</label>
      <input
        type="file"
        name="previous_reports[]"
        accept=".pdf,.doc,.docx"
        multiple
        @change="handlePreviousReportsChange"
        class="formkit-file-input"
      />
      <p class="formkit-help">Formatos aceptados: PDF, Word (Máx. 5MB por archivo)</p>
    </div>
  </div>
</template>

<script setup>
const emit = defineEmits(['update:formData']);

const handleMedicalReportChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    emit('update:formData', { medical_report: file });
  }
};

const handlePreviousReportsChange = (event) => {
  const files = Array.from(event.target.files);
  if (files.length > 0) {
    emit('update:formData', { previous_reports: files });
  }
};
</script>
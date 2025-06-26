<template>
  <div class="space-y-6">
    <!-- 
      =====================================================
      SECCIÓN: INFORME MÉDICO (ARCHIVO ÚNICO)
      =====================================================
      Este campo permite cargar el informe médico obligatorio.
      Es un campo requerido para continuar.
    -->
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
      
      <!-- Mostrar nombre del archivo cargado -->
      <p v-if="formData.medical_report && typeof formData.medical_report === 'object' && formData.medical_report.name" class="text-sm mt-1 text-gray-700">
        Archivo seleccionado: {{ formData.medical_report.name }}
      </p>
    </div>

    <!-- 
      =====================================================
      SECCIÓN: INFORMES PREVIOS (MÚLTIPLES ARCHIVOS)
      =====================================================
      Este campo permite cargar informes previos opcionales.
      El usuario puede seleccionar múltiples archivos.
    -->
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

      <!-- Mostrar lista de archivos cargados -->
      <ul v-if="formData.previous_reports && formData.previous_reports.length" class="mt-1 text-sm text-gray-700 list-disc list-inside">
        <li v-for="(file, index) in formData.previous_reports" :key="index">{{ file.name }}</li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';

const emit = defineEmits(['update:formData']);

const props = defineProps({
  formData: {
    type: Object,
    required: true
  }
});

// Inicializar campos para evitar undefined
onMounted(() => {
  if (!props.formData.medical_report) {
    emit('update:formData', { medical_report: null });
  }
  if (!props.formData.previous_reports) {
    emit('update:formData', { previous_reports: [] });
  }
});

const handleMedicalReportChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    if (file.size > 5 * 1024 * 1024) {
      alert('El archivo excede el tamaño máximo de 5MB');
      event.target.value = '';
      return;
    }
    emit('update:formData', { medical_report: file });
  }
};

const handlePreviousReportsChange = (event) => {
  const files = Array.from(event.target.files);

  const validFiles = files.filter(file => file.size <= 5 * 1024 * 1024);
  if (validFiles.length !== files.length) {
    alert('Algunos archivos exceden el tamaño máximo de 5MB y no fueron seleccionados');
  }
  if (validFiles.length > 0) {
    emit('update:formData', { previous_reports: validFiles });
  }
};
</script>

<style scoped>
.formkit-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

.formkit-file-input {
  @apply block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100;
}

.formkit-help {
  @apply mt-1 text-xs text-gray-500;
}
</style>

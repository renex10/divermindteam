<!-- resources/js/Components/StudentForm/DocumentsStep.vue -->
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
    </div>
  </div>
</template>

<script setup>
// =================================================================
// MÉTODOS PRINCIPALES - MANEJO DE ARCHIVOS
// =================================================================

/**
 * Emitimos eventos personalizados para actualizar el formulario padre
 */
const emit = defineEmits(['update:formData']);

/**
 * Maneja el cambio en el campo de informe médico
 * 
 * Flujo de datos:
 * 1. Usuario selecciona un archivo
 * 2. Capturamos el primer archivo del input
 * 3. Emitimos actualización al componente padre (Wizard)
 * 
 * @param {Event} event - Evento change del input file
 */
const handleMedicalReportChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    // Validación básica de tamaño (5MB)
    if (file.size > 5 * 1024 * 1024) {
      alert('El archivo excede el tamaño máximo de 5MB');
      event.target.value = ''; // Limpiar selección
      return;
    }
    
    // Actualizamos formData.medical_report
    emit('update:formData', { medical_report: file });
  }
};

/**
 * Maneja el cambio en el campo de informes previos
 * 
 * Flujo de datos:
 * 1. Usuario selecciona uno o más archivos
 * 2. Convertimos FileList a Array
 * 3. Emitimos actualización al componente padre (Wizard)
 * 
 * @param {Event} event - Evento change del input file
 */
const handlePreviousReportsChange = (event) => {
  const files = Array.from(event.target.files);
  
  // Filtrar archivos que excedan el tamaño máximo
  const validFiles = files.filter(file => file.size <= 5 * 1024 * 1024);
  
  if (validFiles.length !== files.length) {
    alert('Algunos archivos exceden el tamaño máximo de 5MB y no fueron seleccionados');
  }
  
  if (validFiles.length > 0) {
    // Actualizamos formData.previous_reports
    emit('update:formData', { previous_reports: validFiles });
  }
};
</script>

<!-- 
  =================================================================
  ESTILOS COMPONENTE
  =================================================================
  Nota: Los estilos están usando clases de Tailwind CSS.
  Las clases formkit-* son personalizaciones específicas del sistema.
-->
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
<!-- resources/js/Components/StudentForm/ConsentsStep.vue -->
<template>
  <div class="space-y-6">
    <!-- 
      =====================================================
      SECCIÓN: CONSENTIMIENTO PIE
      =====================================================
      Este checkbox es obligatorio para la participación del estudiante
      en el Programa de Integración Escolar (PIE).
      Al marcarlo, se habilitan los campos del apoderado.
    -->
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

    <!-- 
      =====================================================
      SECCIÓN: AUTORIZACIÓN DE DATOS
      =====================================================
      Este checkbox autoriza el tratamiento de datos personales
      del estudiante con fines educativos.
      Es un requisito legal fundamental según la LGPD.
    -->
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

    <!-- 
      =====================================================
      SECCIÓN: DATOS DEL APODERADO (CONDICIONAL)
      =====================================================
      Estos campos solo son requeridos si se otorga consentimiento PIE.
      Capturan información legalmente necesaria del responsable.
    -->
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
// =================================================================
// PROPS (PROPIEDADES RECIBIDAS)
// =================================================================
defineProps({
  // Objeto reactivo que almacena todos los datos del formulario
  formData: {
    type: Object,
    required: true
  }
});

// =================================================================
// LÓGICA DE VISUALIZACIÓN CONDICIONAL
// =================================================================
/**
 * Los campos de apoderado solo son requeridos cuando:
 *   formData.consent_pie === true
 * 
 * Esto se implementa mediante validación condicional:
 *   :validation="formData.consent_pie ? 'required' : ''"
 * 
 * Cuando consent_pie es verdadero, se aplica validación 'required'
 * Cuando es falso, no se aplica ninguna validación
 */
</script>

<!-- 
  =================================================================
  ESTILOS COMPONENTE
  =================================================================
  Nota: Los estilos están usando clases de Tailwind CSS.
  Se destaca el diseño con fondos azules para resaltar la importancia legal.
-->
<style scoped>
/* Estilos para las secciones de consentimiento */
.bg-blue-50 {
  background-color: #eff6ff; /* Fondo azul claro */
}

.border-blue-100 {
  border-color: #dbeafe; /* Borde azul suave */
}

/* Estilos para etiquetas */
.formkit-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

/* Estilos para campos de texto */
.formkit-input {
  @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border;
}
</style>
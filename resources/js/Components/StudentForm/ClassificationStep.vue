<!-- resources/js/Components/StudentForm/ClassificationStep.vue -->
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- 
      =====================================================
      CAMPO: TIPO DE NECESIDAD
      =====================================================
      Este campo clasifica la necesidad educativa del estudiante
      como Permanente o Transitoria.
      Es fundamental para determinar el tipo de apoyo requerido.
    -->
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
    
    <!-- 
      =====================================================
      CAMPO: PRIORIDAD
      =====================================================
      Este campo determina el nivel de urgencia del apoyo requerido.
      Los valores están predefinidos como:
        - 1: Máxima
        - 2: Media
        - 3: Básica
    -->
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

    <!-- 
      =====================================================
      CAMPO: NECESIDADES ESPECÍFICAS
      =====================================================
      Área de texto para describir detalladamente las necesidades
      educativas especiales del estudiante.
      Ocupa el ancho completo en dispositivos medianos y grandes.
    -->
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
// DATOS REACTIVOS - OPCIONES DE PRIORIDAD
// =================================================================
/**
 * Define las opciones para el campo de prioridad.
 * 
 * Este dato podría venir de externalData en el futuro para hacerlo
 * configurable, pero actualmente está definido localmente.
 * 
 * Nota: Los valores numéricos (1, 2, 3) se usan para facilitar
 * el ordenamiento y procesamiento en el backend.
 */
const priorityOptions = [
  { value: 1, label: 'Máxima' },    // Requiere atención inmediata
  { value: 2, label: 'Media' },     // Atención necesaria pero no urgente
  { value: 3, label: 'Básica' }     // Necesidades de apoyo continuo
];
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
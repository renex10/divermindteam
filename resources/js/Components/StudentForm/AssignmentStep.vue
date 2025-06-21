<!-- resources/js/Components/StudentForm/AssignmentStep.vue -->
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
import { computed } from 'vue';

// =================================================================
// PROPS (PROPIEDADES RECIBIDAS)
// =================================================================
const props = defineProps({
  // Objeto reactivo con datos del formulario
  formData: {
    type: Object,
    required: true
  },
  
  // Datos externos (especialistas, cursos, etc.)
  externalData: {
    type: Object,
    default: () => ({})
  }
});

// =================================================================
// TRANSFORMACIÓN DE ESPECIALISTAS
// =================================================================
/**
 * Transforma los especialistas a formato compatible con FormKit
 * 
 * Flujo de datos:
 * 1. Backend: Obtiene profesionales con usuarios
 * 2. Student.vue: Recibe especialistas como prop
 * 3. Se pasan a través de los componentes hasta aquí
 * 
 * Estructura esperada en externalData.specialists:
 * [
 *   { id: 1, name: "Juan", last_name: "Pérez", ... },
 *   ...
 * ]
 */
const specialistOptions = computed(() => {
  // 1. Verificar si hay especialistas
  if (!props.externalData?.specialists) {
    console.error("No se recibieron especialistas");
    return [];
  }
  
  // 2. Validar tipo de datos
  if (!Array.isArray(props.externalData.specialists)) {
    console.error("'specialists' debe ser un array", props.externalData.specialists);
    return [];
  }
  
  // 3. Transformar a formato {value, label}
  return props.externalData.specialists.map(specialist => {
    return {
      value: specialist.id,
      label: `${specialist.name} ${specialist.last_name}`
    };
  });
});

// Debug: Verificar opciones transformadas
console.log("Opciones de especialistas:", specialistOptions.value);
</script>

<!-- 
  =================================================================
  ESTILOS COMPONENTE
  =================================================================
  Estilos consistentes con el resto del formulario
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
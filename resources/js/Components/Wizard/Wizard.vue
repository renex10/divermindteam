<!-- resources/js/Components/Wizard/Wizard.vue -->
<template>
  <div>
    <!-- Indicador de pasos -->
    <div class="wizard-steps">
      <div v-for="(step, index) in steps" :key="index" class="wizard-step">
        <WizardStep
          :index="index"
          :label="step.label"
          :is-active="currentStep === index"
          :is-completed="index < currentStep"
          :is-last="index === steps.length - 1"
        />
      </div>
    </div>

    <!-- Contenido del paso actual -->
    <div class="mt-8">
      <component
        :is="steps[currentStep].component"
        :formData="formData"
        :externalData="externalData"
        @update:formData="updateFormData"
      />
    </div>

    <!-- Navegación -->
    <div class="mt-8 flex justify-between pt-6 border-t border-gray-200">
      <button 
        v-if="currentStep > 0"
        type="button"
        @click="prevStep"
        class="btn-secondary"
      >
        <ArrowLeftIcon class="h-4 w-4 mr-1" />
        Anterior
      </button>
      <div v-else></div>
      
      <button 
        v-if="currentStep < steps.length - 1"
        type="button"
        @click="nextStep"
        class="btn-primary"
      >
        Siguiente
        <ArrowRightIcon class="h-4 w-4 ml-1" />
      </button>
      <button
        v-else
        type="button"
        @click="submitForm"
        class="btn-primary"
      >
        {{ submitLabel }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits, defineProps } from 'vue';
import { ArrowLeftIcon, ArrowRightIcon } from '@heroicons/vue/24/outline';
import WizardStep from '@/Components/Wizard/WizardStep.vue';

const props = defineProps({
  steps: {
    type: Array,
    required: true,
    default: () => []
  },
  formData: {
    type: Object,
    required: true
  },
  externalData: {
    type: Object,
    default: () => ({})
  },
  submitLabel: {
    type: String,
    default: 'Enviar'
  }
});

const emit = defineEmits(['update:formData', 'next', 'prev', 'submit']);

const currentStep = ref(0);

const updateFormData = (newData) => {
  emit('update:formData', newData);
};

const nextStep = () => {
  if (currentStep.value < props.steps.length - 1) {
    currentStep.value++;
    emit('next', currentStep.value);
  }
};

const prevStep = () => {
  if (currentStep.value > 0) {
    currentStep.value--;
    emit('prev', currentStep.value);
  }
};

const submitForm = () => {
  emit('submit');
};
</script>

<style scoped>
.wizard-steps {
  @apply flex justify-between items-center mb-8;
}

.wizard-step {
  @apply flex-1 flex items-center;
}

.btn-primary {
  @apply inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors;
}

.btn-secondary {
  @apply inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors;
}
</style>
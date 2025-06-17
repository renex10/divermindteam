<template>

<!--      crea un componente de formulario genérico -->
  <FormKit
    type="form"
    :actions="false"
    @submit="submitForm"
  >
    <div class="wizard-steps mb-6">
      <div v-for="(step, index) in steps" :key="index" class="wizard-step">
        <div 
          :class="[
            'step-number',
            currentStep === index ? 'bg-indigo-600 text-white' : 'bg-gray-200',
            index < currentStep ? 'bg-green-500 text-white' : ''
          ]"
        >
          <span v-if="index < currentStep">
            <CheckIcon class="h-4 w-4" />
          </span>
          <span v-else>{{ index + 1 }}</span>
        </div>
        <div class="step-label">{{ step.label }}</div>
        <div v-if="index < steps.length - 1" class="step-line"></div>
      </div>
    </div>

    <div v-for="(step, index) in steps" :key="'step-'+index" v-show="currentStep === index">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <FormKit
          v-for="field in step.fields"
          :key="field.name"
          v-bind="field"
          :label="field.label"
          :name="field.name"
          :validation="field.validation"
        />
      </div>
    </div>

    <div class="mt-8 flex justify-between">
      <button 
        v-if="currentStep > 0"
        type="button"
        @click="prevStep"
        class="btn-secondary"
      >
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
      </button>
      <FormKit
        v-else
        type="submit"
        label="Enviar"
        input-class="btn-primary"
      />
    </div>
  </FormKit>
</template>

<script setup>
import { ref } from 'vue';
import { CheckIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  steps: {
    type: Array,
    required: true,
    default: () => []
  },
  context: {
    type: String,
    default: 'student'
  }
});

const emit = defineEmits(['submit']);

const currentStep = ref(0);
const formData = ref({});

const nextStep = () => {
  if (currentStep.value < props.steps.length - 1) {
    currentStep.value++;
  }
};

const prevStep = () => {
  if (currentStep.value > 0) {
    currentStep.value--;
  }
};

const submitForm = (data) => {
  emit('submit', { ...data, context: props.context });
};
</script>

<style scoped>
.wizard-steps {
  @apply flex justify-between items-center mb-8;
}

.wizard-step {
  @apply flex flex-col items-center relative flex-1;
}

.step-number {
  @apply rounded-full h-8 w-8 flex items-center justify-center mb-2 z-10;
}

.step-label {
  @apply text-xs text-center font-medium text-gray-600;
}

.step-line {
  @apply absolute h-0.5 bg-gray-300 top-4 w-full;
  left: 50%;
  transform: translateX(50%);
}

.btn-primary {
  @apply bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700;
}

.btn-secondary {
  @apply bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400;
}
</style>
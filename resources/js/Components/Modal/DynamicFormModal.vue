<template>
  <Transition name="modal-fade">
    <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
      <!-- ... estructura completa del modal ... -->
      
      <div class="px-6 pt-6">
        <div class="wizard-steps">
          <div v-for="(step, index) in formConfig.steps" :key="index" class="wizard-step">
            <!-- ... indicador de pasos ... -->
          </div>
        </div>
      </div>

      <div class="px-6 py-4">
        <component
          :is="currentStepComponent"
          :formData="formData"
          :externalData="externalData"
          @update:formData="updateFormData"
          @next="nextStep"
          @prev="prevStep"
        />
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  isOpen: Boolean,
  formConfig: Object,
  externalData: Object
});

const emit = defineEmits(['close', 'submit']);

const currentStep = ref(0);
const formData = ref({ ...props.formConfig.initialData });

const currentStepComponent = computed(() => {
  return props.formConfig.steps[currentStep.value]?.component;
});

const updateFormData = (newData) => {
  formData.value = { ...formData.value, ...newData };
};

const nextStep = () => {
  if (currentStep.value < props.formConfig.steps.length - 1) {
    currentStep.value++;
  }
};

const prevStep = () => {
  if (currentStep.value > 0) {
    currentStep.value--;
  }
};

const closeModal = () => {
  currentStep.value = 0;
  formData.value = { ...props.formConfig.initialData };
  emit('close');
};

const handleSubmit = () => {
  emit('submit', formData.value);
  closeModal();
};
</script>
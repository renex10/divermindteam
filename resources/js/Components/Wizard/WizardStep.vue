<!-- resources/js/Components/Wizard/WizardStep.vue -->
<template>
  <div class="flex items-center">
    <div class="step-indicator">
      <div :class="[
        'step-number',
        isActive ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-gray-300',
        isCompleted ? 'bg-green-500 border-green-500' : ''
      ]">
        <CheckIcon v-if="isCompleted" class="h-4 w-4" />
        <span v-else>{{ index + 1 }}</span>
      </div>
      <div class="step-label">{{ label }}</div>
    </div>
    <div v-if="!isLast" class="step-connector"></div>
  </div>
</template>

<script setup>
import { CheckIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  index: Number,
  label: String,
  isActive: Boolean,
  isCompleted: Boolean,
  isLast: Boolean
});
</script>

<style scoped>
.step-indicator {
  @apply flex flex-col items-center relative;
}

.step-number {
  @apply rounded-full h-8 w-8 flex items-center justify-center border-2 mb-2 z-10 transition-colors duration-300;
}

.step-label {
  @apply text-xs text-center font-medium text-gray-600;
}

.step-connector {
  @apply flex-1 h-0.5 bg-gray-200 mx-2;
}
</style>
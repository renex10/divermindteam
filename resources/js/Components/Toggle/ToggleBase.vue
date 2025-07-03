<template>
  <label class="inline-flex items-center cursor-pointer">
    <input 
      type="checkbox"
      :checked="modelValue"
      @change="$emit('update:modelValue', $event.target.checked)"
      class="sr-only peer"
      :disabled="disabled"
    >
    <!-- Indicador visual con transición -->
    <div :class="[
      'relative w-11 h-6 rounded-full peer',
      'after:content-[\'\'] after:absolute after:top-0.5 after:start-[2px]',
      'after:bg-white after:border after:rounded-full after:h-5 after:w-5',
      'after:transition-all peer-checked:after:translate-x-full',
      modelValue 
        ? 'bg-green-600 peer-checked:bg-green-600' 
        : 'bg-gray-200 dark:bg-gray-700'
    ]"></div>
    <span class="ms-3 text-sm font-medium text-gray-900">
      {{ modelValue ? activeLabel : inactiveLabel }}
    </span>
  </label>
</template>

<script setup>
defineProps({
  modelValue: Boolean,
  disabled: {
    type: Boolean,
    default: false
  },
  activeLabel: {
    type: String,
    default: 'Activo'
  },
  inactiveLabel: {
    type: String,
    default: 'Inactivo'
  }
});

defineEmits(['update:modelValue']);
</script>
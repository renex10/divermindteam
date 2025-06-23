<template>
  <div class="p-6 rounded-lg shadow text-white relative" :class="gradientClasses">
    <div v-if="$slots.icon" class="absolute top-4 right-4">
      <slot name="icon"></slot>
    </div>
    
    <h3 class="text-lg font-medium">{{ title }}</h3>
    <p class="text-3xl font-bold mt-2">{{ value }}</p>
    
    <div v-if="$slots.tooltip" class="mt-2">
      <slot name="tooltip"></slot>
    </div>
    
    <slot></slot>
  </div>
</template>
<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [Number, String],
    required: true
  },
  gradientClasses: {
    type: String,
    default: 'bg-gradient-to-r from-indigo-500 to-purple-600'
  }
});
</script>
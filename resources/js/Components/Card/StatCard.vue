<!-- resources/js/Components/Card/StatCard.vue -->
<template>
  <div class="p-6 rounded-lg shadow text-white relative" :class="gradientClasses">
    <h3 class="text-lg font-medium">{{ title }}</h3>
    
    <!-- Mostrar valor con animación -->
    <p class="text-3xl font-bold mt-2 relative">
      <span>{{ value }}</span>
      <span v-if="showChangeIndicator" class="change-indicator" :class="changeClass">
        {{ changeDirection === 'up' ? '↑' : '↓' }}
      </span>
    </p>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  title: String,
  value: [Number, String],
  gradientClasses: String
});

const previousValue = ref(props.value);
const showChangeIndicator = ref(false);
const changeDirection = ref(null);
const changeClass = ref('');

watch(() => props.value, (newVal, oldVal) => {
  if (newVal !== oldVal) {
    showChangeIndicator.value = true;
    changeDirection.value = newVal > oldVal ? 'up' : 'down';
    changeClass.value = changeDirection.value === 'up' ? 'text-green-300' : 'text-red-300';
    
    // Ocultar el indicador después de 2 segundos
    setTimeout(() => {
      showChangeIndicator.value = false;
    }, 2000);
    
    previousValue.value = newVal;
  }
});
</script>

<style scoped>
.change-indicator {
  position: absolute;
  margin-left: 8px;
  font-size: 1.5rem;
  animation: pulse 0.5s ease-in-out;
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.2); }
  100% { transform: scale(1); }
}
</style>
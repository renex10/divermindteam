<template>
  <transition name="fade">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-dark bg-opacity-90">
      <div class="loader">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
      </div>
    </div>
  </transition>
</template>

<script setup>
defineProps({
  show: Boolean
})
</script>

<style scoped>
/* Estilos del loader */
.loader {
  --dim: 3rem;
  width: var(--dim);
  height: var(--dim);
  position: relative;
  animation: spin988 2s linear infinite;
}

.loader .circle {
  --color: #3758F9; /* Color que combina con tu diseño */
  --dim: 1.2rem;
  width: var(--dim);
  height: var(--dim);
  background-color: var(--color);
  border-radius: 50%;
  position: absolute;
}

.loader .circle:nth-child(1) { top: 0; left: 0; }
.loader .circle:nth-child(2) { top: 0; right: 0; }
.loader .circle:nth-child(3) { bottom: 0; left: 0; }
.loader .circle:nth-child(4) { bottom: 0; right: 0; }

@keyframes spin988 {
  0% { transform: scale(1) rotate(0); }
  20%, 25% { transform: scale(1.3) rotate(90deg); }
  45%, 50% { transform: scale(1) rotate(180deg); }
  70%, 75% { transform: scale(1.3) rotate(270deg); }
  95%, 100% { transform: scale(1) rotate(360deg); }
}

/* Transición suave */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
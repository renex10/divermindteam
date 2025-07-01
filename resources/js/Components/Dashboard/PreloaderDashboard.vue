<template>
  <div class="fixed inset-0 z-[100] flex justify-center items-center bg-white bg-opacity-90">
    <div class="relative w-24 h-24 animate-spin rounded-full bg-gradient-to-r from-purple-400 via-blue-500 to-red-400">
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-white rounded-full"></div>
    </div>
    <p class="absolute bottom-1/4 text-gray-700 font-medium mt-4">Cargando contenido...</p>
  </div>
</template>

<script>
export default {
  name: 'PreloaderDashboard'
}
</script>

<style scoped>
/* Animación del spinner */
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>
<template>
  <div class="min-h-screen flex flex-col">
    <Navigation />
    <Preloader :show="isLoading"/>
    
    <main class="flex-1">
      <slot />
      <FooterLayout/>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { router } from '@inertiajs/vue3'

import Preloader from '@/Components/Landing/preloader/Preloader.vue'
import Navigation from '@/Components/Landing/navigation/Navigation.vue'
import FooterLayout from '@/Components/Landing/footer/FooterLayout.vue'

const isLoading = ref(true)

let removeStartListener, removeFinishListener, removeErrorListener

onMounted(() => {
  const timer = setTimeout(() => isLoading.value = false, 600)
  
  // Usar los listeners de Inertia correctamente
  removeStartListener = router.on('start', () => {
    isLoading.value = true
    clearTimeout(timer)
  })
  
  removeFinishListener = router.on('finish', () => {
    isLoading.value = false
  })
  
  removeErrorListener = router.on('error', () => {
    isLoading.value = false
  })
})

onBeforeUnmount(() => {
  // Remover listeners usando las funciones de remoción
  if (removeStartListener) removeStartListener()
  if (removeFinishListener) removeFinishListener()
  if (removeErrorListener) removeErrorListener()
})
</script>
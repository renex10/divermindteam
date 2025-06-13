<template>
  <button
    @click="$emit('click')"
    class="fixed top-4 left-4 z-50 p-3 bg-gray-800 text-white rounded-lg shadow-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors lg:hidden"
    aria-label="Abrir menú de navegación"
  >
    <Bars3Icon class="w-6 h-6" />
  </button>
</template>

<script setup>
import { Bars3Icon } from '@heroicons/vue/24/outline'

// Emits
defineEmits(['click'])
</script>
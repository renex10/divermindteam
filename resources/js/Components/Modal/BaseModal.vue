<!-- resources/js/Components/Modal/BaseModal.vue -->
<template>
  <Transition name="modal-fade">
    <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-900 bg-opacity-75"></div>
        </div>

        <div 
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
          role="dialog" 
          aria-modal="true"
          aria-labelledby="modal-headline"
        >
          <!-- Header -->
          <div class="bg-indigo-700 px-6 py-4 sm:px-6 rounded-t-lg">
            <div class="flex items-center justify-between">
              <h3 class="text-xl font-semibold text-white">{{ title }}</h3>
              <button @click="closeModal" class="text-indigo-200 hover:text-white focus:outline-none">
                <XMarkIcon class="h-6 w-6" />
              </button>
            </div>
          </div>

          <!-- Contenido principal -->
          <div class="px-6 py-4">
            <slot></slot>
          </div>

          <!-- Footer (opcional) -->
          <div v-if="$slots.footer" class="bg-gray-50 px-6 py-4 sm:px-6 rounded-b-lg">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  isOpen: Boolean,
  title: {
    type: String,
    default: 'Modal'
  }
});

const emit = defineEmits(['close']);

const closeModal = () => {
  emit('close');
};
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
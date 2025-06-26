<script setup>
import { onMounted, ref } from 'vue'
import { gsap } from 'gsap'
import TypewriterEffect from '@/Components/Landing/typewriter/TypewriterEffect.vue'

const imagen = ref(null)

onMounted(() => {
  gsap.to(imagen.value, {
    rotation: 5,
    y: -10,
    duration: 3,
    repeat: -1,
    yoyo: true,
    ease: 'sine.inOut',
    transformOrigin: '50% 50%',
  })
})
</script>

<template>
  <section class="w-full h-screen bg-[#d9dcd6] px-6 md:px-12 flex items-center">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 w-full gap-12">
      
      <!-- Lado izquierdo con Typewriter mejorado -->
      <div class="flex flex-col justify-center h-full space-y-8 text-center md:text-left">
        <TypewriterEffect />

        <p class="text-lg text-[#2f6690] max-w-xl mx-auto md:mx-0 leading-relaxed">
          Nuestro compromiso es acompañarte en cada paso con empatía y tecnología inclusiva.
        </p>

        <div>
          <button
            class="bg-[#3a7ca5] text-white px-6 py-3 rounded-lg shadow-lg hover:bg-[#2f6690] transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2f6690]"
          >
            Conócenos
          </button>
        </div>
      </div>

      <!-- Imagen animada a la derecha -->
      <div class="flex justify-center items-center h-full">
        <img
          ref="imagen"
          src="/img/code.png"
          alt="Hero"
          class="w-72 md:w-80 lg:w-96 h-auto rounded-xl shadow-lg"
        />
      </div>
    </div>
  </section>
</template>
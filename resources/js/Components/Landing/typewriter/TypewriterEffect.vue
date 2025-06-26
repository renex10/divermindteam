<script setup>
import { onMounted } from 'vue'
import Typed from 'typed.js'

onMounted(() => {
  new Typed('#typed-palabra', {
    strings: [
      'poyo',
      'compaño',
      'mo',
      'gradezco',
      'cepto',
      'comparto',
      'brigo',
      'cojo'
    ],
    typeSpeed: 80,
    backSpeed: 40,
    backDelay: 2500,
    startDelay: 500,
    loop: true,
    showCursor: false,
  })
})
</script>

<template>
  <div class="text-5xl md:text-6xl font-semibold text-center md:text-left leading-snug">
    <span class="text-[#81c3d7] font-bold">Yo</span>
    <span class="text-[#16425b] font-black font-stretch-ultra-expanded"> TEA</span>
    <span id="typed-palabra" class="text-[#3a7ca5] font-semibold"></span>
  </div>
</template>
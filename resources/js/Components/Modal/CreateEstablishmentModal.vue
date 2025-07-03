<script setup>
import BaseModal from '@/Components/Modal/BaseModal.vue'
import { useForm } from '@inertiajs/vue3'

import { ref, computed, watch } from 'vue'
import { FormKit } from '@formkit/vue'


const props = defineProps({
  isOpen: Boolean,
  regiones: Array,
  comunas: Array
})

// ✳️ MODIFICACIÓN: declaramos eventos emitidos, incluyendo el nuevo evento `saved`
// que se emitirá al guardar exitosamente un establecimiento.
const emit = defineEmits(['close', 'saved'])

const form = useForm({
  name: '',
  address: '',
  region_id: '',
  commune_id: '',
  pie_quota_max: 0,
  is_active: true,
})

// 🔁 Computada: Filtrar comunas según región seleccionada
const comunasFiltradas = computed(() => {
  if (!form.region_id) return []
  return props.comunas.filter(c => c.region_id === form.region_id)
})

// 👁️‍🗨️ Observador: reiniciar comuna al cambiar región
watch(() => form.region_id, (val) => {
  console.log('🔁 Región seleccionada:', val)
  form.commune_id = '' // Evita enviar comuna vieja
})

watch(() => form.commune_id, (val) => {
  console.log('📍 Comuna seleccionada:', val)
})

const submit = () => {
  console.log('📤 Enviando form:', form)

  // 📌 MODIFICACIÓN IMPORTANTE:
  // Emitimos `saved` al padre cuando el formulario se guarda correctamente.
  // Esto permite al componente padre (`Index.vue`) recargar los datos desde el backend
  // usando `Inertia.reload({ only: ['establishments'] })`, asegurando datos frescos.
  form.post(route('establishments.store'), {
    onSuccess: () => {
      emit('saved')     // ✅ Notificamos al padre para que recargue datos
      emit('close')     // ✅ Cerramos el modal
      form.reset()      // ✅ Limpiamos el formulario
    },
    onError: (errors) => {
      console.error('❌ Errores al guardar:', errors)
    }
  })
}
</script>

<template>
  <BaseModal :isOpen="props.isOpen" title="Nuevo Establecimiento" @close="emit('close')">
    <form @submit.prevent="submit" class="space-y-4">
      <FormKit type="text" label="Nombre" v-model="form.name" name="name" validation="required" />
      <FormKit type="text" label="Dirección" v-model="form.address" name="address" validation="required" />
      
      <FormKit
        type="select"
        label="Región"
        v-model="form.region_id"
        name="region_id"
        :options="props.regiones.map(r => ({ label: r.name, value: r.id }))"
        validation="required"
      />

      <FormKit
        type="select"
        label="Comuna"
        v-model="form.commune_id"
        name="commune_id"
        :options="comunasFiltradas.map(c => ({ label: c.name, value: c.id }))"
        validation="required"
        :disabled="!form.region_id"
      />

      <FormKit type="number" label="Cupo PIE" v-model="form.pie_quota_max" name="pie_quota_max" validation="required|min:0" />
      
      <FormKit
        type="checkbox"
        label="Activo"
        v-model="form.is_active"
        name="is_active"
      />

      <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
  </BaseModal>
</template>

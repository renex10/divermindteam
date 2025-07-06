<script setup>
import BaseModal from '@/Components/Modal/BaseModal.vue'
import { useForm, router } from '@inertiajs/vue3'
import { computed, watch, ref } from 'vue'
import { FormKit } from '@formkit/vue'
import { useToast } from 'vue-toast-notification'
import 'vue-toast-notification/dist/theme-bootstrap.css'

const $toast = useToast()

const props = defineProps({
  isOpen: Boolean,
  establishment: {
    type: Object,
    default: () => ({})
  },
  regiones: {
    type: Array,
    default: () => []
  },
  comunas: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['close', 'updated'])

// Inicialización segura de propiedades
const safeEstablishment = ref(props.establishment || {})
const safeCommune = ref(safeEstablishment.value.commune || {})

// Propiedades computadas seguras con valores por defecto
const regionesSeguras = computed(() => props.regiones || [])
const comunasSeguras = computed(() => props.comunas || [])

// Formulario con datos iniciales y valores por defecto seguros
const form = useForm({
  rbd: safeEstablishment.value.rbd || '',
  name: safeEstablishment.value.name || '',
  address: safeEstablishment.value.address || '',
  region_id: safeCommune.value.region_id || '',
  commune_id: safeEstablishment.value.commune_id || '',
  pie_quota_max: safeEstablishment.value.pie_quota_max || 0,
  is_active: safeEstablishment.value.is_active ?? true,
})

// Computed para filtrar comunas con manejo seguro
const comunasFiltradas = computed(() => {
  if (!form.region_id) return []
  return (comunasSeguras.value || []).filter(c => c?.region_id === form.region_id)
})

// Watchers para cambios en región/comuna
watch(() => form.region_id, (val) => {
  if (val !== safeCommune.value.region_id) {
    form.commune_id = ''
  }
})

// Validación del formulario
const validateForm = () => {
  const errors = {}
  
  if (!form.name || form.name.length < 3) {
    errors.name = 'El nombre debe tener al menos 3 caracteres'
  }
  
  if (!form.rbd || form.rbd < 1000 || form.rbd > 999999) {
    errors.rbd = 'El RBD debe ser un número entre 1000 y 999999'
  }
  
  if (!form.address || form.address.length < 5) {
    errors.address = 'La dirección debe tener al menos 5 caracteres'
  }
  
  if (!form.region_id) {
    errors.region_id = 'Debe seleccionar una región'
  }
  
  if (!form.commune_id) {
    errors.commune_id = 'Debe seleccionar una comuna'
  }
  
  // Validar relación región-comuna
  if (form.region_id && form.commune_id) {
    const selectedCommune = comunasSeguras.value.find(c => c?.id === form.commune_id)
    if (selectedCommune && selectedCommune.region_id !== form.region_id) {
      errors.commune_id = 'La comuna seleccionada no pertenece a la región'
    }
  }
  
  if (!form.pie_quota_max || form.pie_quota_max < 0) {
    errors.pie_quota_max = 'El cupo PIE debe ser un número positivo'
  }
  
  return Object.keys(errors).length ? errors : null
}

/// Reemplaza la función submit en tu componente Vue
const submit = () => {
  const validationErrors = validateForm()
  if (validationErrors) {
    form.errors = validationErrors
    return
  }

  // Preparar datos para el backend
  const formData = {
    rbd: form.rbd,
    name: form.name,
    address: form.address,
    commune_id: form.commune_id,
    pie_quota_max: form.pie_quota_max,
    is_active: form.is_active
  }

  // SOLUCIÓN: Usar URL directa en lugar del helper route()
  const updateUrl = `/establishments/${safeEstablishment.value.id}`
  
  router.put(updateUrl, formData, {
    preserveScroll: true,
    onSuccess: () => {
      $toast.success('Establecimiento actualizado correctamente', { 
        position: 'top-right',
        duration: 3000
      })
      emit('updated', { ...safeEstablishment.value, ...formData })
      emit('close')
    },
    onError: (errors) => {
      console.error('Errores de validación:', errors)
      let errorMessage = 'Error al actualizar: '
      if (errors && typeof errors === 'object') {
        errorMessage += Object.values(errors).join(', ')
      } else {
        errorMessage += 'Error desconocido'
      }
      $toast.error(errorMessage, { 
        position: 'top-right', 
        duration: 8000 
      })
    }
  })
}
  // MÉTODO ALTERNATIVO 2: Usar form.put con route helper
  // form.put(route('establishments.update', safeEstablishment.value.id), {
  //   preserveScroll: true,
  //   data: formData,
  //   onSuccess: () => {
  //     $toast.success('Establecimiento actualizado correctamente')
  //     emit('updated', { ...safeEstablishment.value, ...formData })
  //     emit('close')
  //   },
  //   onError: (errors) => {
  //     console.error('Errores:', errors)
  //     $toast.error('Error al actualizar')
  //   }
  // })


// Función para cerrar modal
function handleClose() {
  form.reset()
  form.clearErrors()
  emit('close')
}

console.log('Establecimiento recibido:', props.establishment)
console.log('ID del establecimiento:', safeEstablishment.value.id)
</script>

<template>
  <BaseModal 
    :isOpen="isOpen" 
    title="Editar Establecimiento" 
    @close="handleClose"
    maxWidth="lg"
  >
    <div class="space-y-6 p-6">
      <form @submit.prevent="submit" class="space-y-4">
        <FormKit 
          type="text" 
          label="Nombre del Establecimiento" 
          v-model="form.name" 
          name="name" 
          validation="required|length:3,255"
          :validation-messages="{
            required: 'El nombre es obligatorio',
            length: 'El nombre debe tener entre 3 y 255 caracteres'
          }"
          :errors="form.errors.name ? [form.errors.name] : []"
        />

        <FormKit 
          type="number" 
          label="RBD (Rol Base de Datos)" 
          v-model="form.rbd" 
          name="rbd" 
          validation="required|number|min:1000|max:999999" 
          :validation-messages="{
            required: 'El RBD es obligatorio',
            number: 'Debe ser un número válido',
            min: 'El RBD debe ser mayor o igual a 1000',
            max: 'El RBD debe ser menor o igual a 999999'
          }"
          :errors="form.errors.rbd ? [form.errors.rbd] : []" 
          step="1" 
        />
        
        <FormKit 
          type="text" 
          label="Dirección" 
          v-model="form.address" 
          name="address" 
          validation="required|length:5,500"
          :validation-messages="{
            required: 'La dirección es obligatoria',
            length: 'La dirección debe tener entre 5 y 500 caracteres'
          }"
          :errors="form.errors.address ? [form.errors.address] : []"
        />
        
        <FormKit
          type="select"
          label="Región"
          v-model="form.region_id"
          name="region_id"
          :options="regionesSeguras.map(r => ({ label: r.name, value: r.id }))"
          validation="required"
          :validation-messages="{
            required: 'Debe seleccionar una región'
          }"
          :errors="form.errors.region_id ? [form.errors.region_id] : []"
        />

        <FormKit
          type="select"
          label="Comuna"
          v-model="form.commune_id"
          name="commune_id"
          :options="comunasFiltradas.map(c => ({ label: c.name, value: c.id }))"
          validation="required"
          :validation-messages="{
            required: 'Debe seleccionar una comuna'
          }"
          :errors="form.errors.commune_id ? [form.errors.commune_id] : []"
          :disabled="!form.region_id"
        />

        <FormKit 
          type="number" 
          label="Cupo PIE Máximo" 
          v-model="form.pie_quota_max" 
          name="pie_quota_max" 
          validation="required|min:0|max:9999"
          :validation-messages="{
            required: 'El cupo PIE es obligatorio',
            min: 'El cupo debe ser un número positivo',
            max: 'El cupo no puede exceder 9999'
          }"
          :errors="form.errors.pie_quota_max ? [form.errors.pie_quota_max] : []"
          step="1"
          min="0"
        />
        
        <FormKit
          type="checkbox"
          label="Establecimiento activo"
          v-model="form.is_active"
          name="is_active"
          :errors="form.errors.is_active ? [form.errors.is_active] : []"
        />

        <div class="flex justify-end space-x-3 pt-4">
          <button 
            type="button" 
            @click="handleClose"
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
            :disabled="form.processing"
          >
            Cancelar
          </button>
          
          <button 
            type="submit"
            :disabled="form.processing"
            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 flex items-center"
          >
            <span v-if="form.processing" class="flex items-center">
              <svg class="animate-spin mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Actualizando...
            </span>
            <span v-else>
              💾 Actualizar Establecimiento
            </span>
          </button>
        </div>
      </form>
    </div>
  </BaseModal>
</template>

<style scoped>
.form-container {
  max-width: 500px;
}
</style>
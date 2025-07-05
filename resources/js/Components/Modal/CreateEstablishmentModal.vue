<script setup>
import BaseModal from '@/Components/Modal/BaseModal.vue'
import { useForm } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import { FormKit } from '@formkit/vue'
// Importar el hook de toast
import { useToast } from 'vue-toast-notification'
import 'vue-toast-notification/dist/theme-bootstrap.css' // Asegúrate de importar el tema

const $toast = useToast() // Inicializar

const props = defineProps({
  isOpen: Boolean,
  regiones: Array,
  comunas: Array
})

const emit = defineEmits(['close', 'saved'])

const form = useForm({
   rbd: '',
  name: '',
  address: '',
  region_id: '',
  commune_id: '',
  pie_quota_max: 0,
  is_active: true,
})

const comunasFiltradas = computed(() => {
  if (!form.region_id) return []
  return props.comunas.filter(c => c.region_id === form.region_id)
})

const regionSeleccionada = computed(() => {
  return props.regiones.find(r => r.id === form.region_id)
})

const comunaSeleccionada = computed(() => {
  return props.comunas.find(c => c.id === form.commune_id)
})

watch(() => form.region_id, (val) => {
  console.log('🔁 Región seleccionada:', val)
  form.commune_id = '' // Evita enviar comuna vieja
})

watch(() => form.commune_id, (val) => {
  console.log('📍 Comuna seleccionada:', val)
})

const submit = () => {
  console.log('📤 Enviando form:', form.data())

  // Validación en el cliente antes de enviar
  if (!form.name || !form.address || !form.region_id || !form.commune_id || form.pie_quota_max === null) {
    $toast.error('Por favor, completa todos los campos obligatorios', {
      position: 'top-right',
      duration: 5000
    })
    return
  }

  form.post(route('establishments.store'), {
    onSuccess: (page) => {
      console.log('✅ Establecimiento creado exitosamente')
      console.log('📄 Respuesta del servidor:', page)
      
      const nuevoEstablecimiento = {
        id: Date.now(), 
        name: form.name,
        rbd: form.rbd,

        address: form.address,
        region_id: form.region_id,
        commune_id: form.commune_id,
        pie_quota_max: form.pie_quota_max,
        is_active: form.is_active,
        commune: {
          id: form.commune_id,
          name: comunaSeleccionada.value?.name || 'Comuna no encontrada',
          region_id: form.region_id,
          region: {
            id: form.region_id,
            name: regionSeleccionada.value?.name || 'Región no encontrada'
          }
        },
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      }
      
      console.log('🏗️ Establecimiento para actualización optimista:', nuevoEstablecimiento)
      
      emit('saved', nuevoEstablecimiento)
      emit('close')
      form.reset()

      // Mostrar toast de éxito
      $toast.success('Establecimiento creado con éxito!', {
        position: 'top-right'
      })
    },
    onError: (errors) => {
      console.error('❌ Errores al guardar:', errors)
      console.error('🔍 Detalles del error:', {
        formData: form.data(),
        errors: errors,
        hasErrors: form.hasErrors,
        processing: form.processing
      })
      
      // Mostrar toast con errores del servidor
      let errorMessage = 'Error al guardar: '
      if (typeof errors === 'object') {
        errorMessage += Object.values(errors).join(', ')
      } else {
        errorMessage += errors
      }
      
      $toast.error(errorMessage, {
        position: 'top-right',
        duration: 8000
      })
    },
    onFinish: () => {
      console.log('🏁 Proceso de guardado terminado')
    }
  })
}

function handleClose() {
  form.reset()
  form.clearErrors()
  emit('close')
}
</script>

<template>
  <BaseModal :isOpen="props.isOpen" title="Nuevo Establecimiento" @close="handleClose">
    <div class="space-y-6">
      <!-- 📋 Formulario -->
      <form @submit.prevent="submit" class="space-y-4">
        
        <!-- 🏷️ Nombre del establecimiento -->
        <FormKit 
          type="text" 
          label="Nombre del Establecimiento" 
          v-model="form.name" 
          name="name" 
          validation="required|length:3,255"
          :errors="form.errors.name ? [form.errors.name] : []"
          help="Ingresa el nombre completo del establecimiento"
        />

        <!-- 🆕 RBD -->
<FormKit 
  type="number" 
  label="RBD (Rol Base de Datos)" 
  v-model="form.rbd" 
  name="rbd" 
  validation="required|number|min:1000|max:999999" 
  :errors="form.errors.rbd ? [form.errors.rbd] : []" 
  help="Código oficial asignado por el Ministerio de Educación" 
  step="1" 
/>
        
        <!-- 📍 Dirección -->
        <FormKit 
          type="text" 
          label="Dirección" 
          v-model="form.address" 
          name="address" 
          validation="required|length:5,500"
          :errors="form.errors.address ? [form.errors.address] : []"
          help="Dirección completa del establecimiento"
        />
        
        <!-- 🌍 Región -->
        <FormKit
          type="select"
          label="Región"
          v-model="form.region_id"
          name="region_id"
          :options="props.regiones.map(r => ({ label: r.name, value: r.id }))"
          validation="required"
          :errors="form.errors.region_id ? [form.errors.region_id] : []"
          help="Selecciona la región donde se ubica el establecimiento"
          placeholder="Selecciona una región"
        />

        <!-- 🏘️ Comuna -->
        <FormKit
          type="select"
          label="Comuna"
          v-model="form.commune_id"
          name="commune_id"
          :options="comunasFiltradas.map(c => ({ label: c.name, value: c.id }))"
          validation="required"
          :errors="form.errors.commune_id ? [form.errors.commune_id] : []"
          :disabled="!form.region_id"
          help="Selecciona la comuna específica"
          placeholder="Primero selecciona una región"
        />

        <!-- 🎯 Cupo PIE -->
        <FormKit 
          type="number" 
          label="Cupo PIE Máximo" 
          v-model="form.pie_quota_max" 
          name="pie_quota_max" 
          validation="required|min:0|max:9999"
          :errors="form.errors.pie_quota_max ? [form.errors.pie_quota_max] : []"
          help="Número máximo de cupos PIE disponibles"
          step="1"
          min="0"
        />
        
        <!-- ✅ Estado activo -->
        <FormKit
          type="checkbox"
          label="Establecimiento activo"
          v-model="form.is_active"
          name="is_active"
          :errors="form.errors.is_active ? [form.errors.is_active] : []"
          help="Marca esta casilla si el establecimiento está operativo"
        />

        <!-- 🔘 Botones de acción -->
        <div class="flex justify-end space-x-3 pt-4">
          <button 
            type="button" 
            @click="handleClose"
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :disabled="form.processing"
          >
            Cancelar
          </button>
          
          <button 
            type="submit"
            :disabled="form.processing"
            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
          >
            <span v-if="form.processing">
              <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Guardando...
            </span>
            <span v-else>
              💾 Guardar Establecimiento
            </span>
          </button>
        </div>
      </form>
    </div>
  </BaseModal>
</template>

<style scoped>
/* Estilos opcionales para mejorar la apariencia */
.form-container {
  max-width: 500px;
}

.form-section {
  border-bottom: 1px solid #e5e7eb;
  padding-bottom: 1.5rem;
  margin-bottom: 1.5rem;
}

.form-section:last-child {
  border-bottom: none;
  margin-bottom: 0;
}

.form-section h3 {
  font-size: 1.125rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 1rem;
}
</style>
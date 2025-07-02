<!-- resources/js/Components/Modal/CreateEstablishmentModal.vue -->
<template>
  <BaseModal :isOpen="isOpen" title="Crear Nuevo Establecimiento" @close="closeModal">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Nombre del Establecimiento -->
      <FormKit
        type="text"
        v-model="formData.name"
        name="name"
        label="Nombre del Establecimiento *"
        placeholder="Ej: Escuela Básica Los Aromos"
        validation="required"
        outer-class="mb-0 md:col-span-2"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- RBD -->
      <FormKit
        type="text"
        v-model="formData.rbd"
        name="rbd"
        label="RBD *"
        placeholder="12345-6"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Tipo de Establecimiento -->
      <FormKit
        type="select"
        v-model="formData.type"
        name="type"
        label="Tipo de Establecimiento *"
        :options="establishmentTypeOptions"
        placeholder="Seleccione tipo"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Dirección -->
      <FormKit
        type="text"
        v-model="formData.address"
        name="address"
        label="Dirección *"
        placeholder="Ej: Av. Principal 123"
        validation="required"
        outer-class="mb-0 md:col-span-2"
        label-class="formkit-label"
        input-class="formkit-input"
      />
    
      <!-- Campo REGIÓN -->
      <FormKit
        type="select"
        v-model="formData.region_id"
        name="region_id"
        label="Región *"
        :options="regionOptions"
        placeholder="Seleccione región"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
        @change="handleRegionChange"
      />

      <!-- Comuna -->
      <FormKit
        type="select"
        v-model="formData.commune_id"
        name="commune_id"
        label="Comuna *"
        :options="filteredCommuneOptions"
        placeholder="Seleccione comuna"
        validation="required"
        :disabled="!formData.region_id"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Niveles que atiende -->
      <FormKit
        type="select"
        v-model="formData.education_levels"
        name="education_levels"
        label="Niveles de Educación"
        :options="educationLevelOptions"
        multiple
        placeholder="Seleccione niveles"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />
    </div>

    <!-- Observaciones -->
    <div class="mt-6">
      <FormKit
        type="textarea"
        v-model="formData.observations"
        name="observations"
        label="Observaciones"
        placeholder="Información adicional sobre el establecimiento..."
        rows="3"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />
    </div>

    <!-- Establecimiento activo -->
    <div class="mt-4">
      <FormKit
        type="checkbox"
        v-model="formData.is_active"
        name="is_active"
        label="Establecimiento activo"
        outer-class="mb-0"
      />
    </div>

    <template #footer>
      <div class="flex justify-end space-x-3">
        <button 
          @click="closeModal"
          type="button"
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300"
        >
          Cancelar
        </button>
        <button 
          @click="handleSubmit"
          type="button"
          :disabled="isSubmitting"
          class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50"
        >
          {{ isSubmitting ? 'Guardando...' : 'Guardar Establecimiento' }}
        </button>
      </div>
    </template>
  </BaseModal>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import BaseModal from '@/Components/Modal/BaseModal.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  isOpen: Boolean,
  regions: {
    type: Array,
    default: () => []
  },
  communes: {
    type: Array,
    default: () => []
  },
  initialData: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['close', 'success']);

const isSubmitting = ref(false);

// DEBUG: Verificar datos recibidos
onMounted(() => {
  console.log('Regiones recibidas en modal:', props.regions);
  console.log('Comunas recibidas en modal:', props.communes);
});

// Opciones de tipo de establecimiento
const establishmentTypeOptions = [
  { value: 'municipal', label: 'Municipal' },
  { value: 'particular_subvencionado', label: 'Particular Subvencionado' },
  { value: 'particular_pagado', label: 'Particular Pagado' },
  { value: 'corporacion', label: 'Corporación' }
];

// Opciones de niveles educativos
const educationLevelOptions = [
  { value: 'parvularia', label: 'Educación Parvularia' },
  { value: 'basica', label: 'Educación Básica' },
  { value: 'media', label: 'Educación Media' },
  { value: 'especial', label: 'Educación Especial' }
];

// Opciones de regiones (con verificación de estructura)
const regionOptions = computed(() => {
  if (!props.regions || !Array.isArray(props.regions)) {
    console.warn('Regiones no es un array válido:', props.regions);
    return [];
  }
  
  // Transformar regiones a formato para FormKit
  return props.regions.map(region => ({
    value: region.id,
    label: region.name
  }));
});

// Comunas filtradas por región seleccionada
const filteredCommuneOptions = computed(() => {
  if (!formData.region_id) {
    return [];
  }
  
  // Filtrar comunas por región seleccionada
  const filtered = props.communes.filter(
    commune => commune.region_id == formData.region_id
  );
  
  // Transformar comunas a formato para FormKit
  return filtered.map(commune => ({
    value: commune.id,
    label: commune.name
  }));
});

// Función para crear datos por defecto
const createDefaultFormData = (initialData = {}) => {
  return {
    name: initialData.name || '',
    rbd: initialData.rbd || '',
    type: initialData.type || 'municipal',
    address: initialData.address || '',
    region_id: initialData.region_id || null,
    commune_id: initialData.commune_id || null,
    phone: initialData.phone || '',
    email: initialData.email || '',
    director_name: initialData.director_name || '',
    total_enrollment: initialData.total_enrollment || 0,
    education_levels: initialData.education_levels || [],
    observations: initialData.observations || '',
    is_active: initialData.is_active !== undefined ? initialData.is_active : true
  };
};

// Datos del formulario
const formData = reactive(createDefaultFormData(props.initialData));

// Manejar cambio de región
const handleRegionChange = () => {
  formData.commune_id = null; // Resetear comuna al cambiar región
};

// Actualizar formData cuando cambien los datos iniciales
watch(() => props.initialData, (newData) => {
  if (newData && Object.keys(newData).length > 0) {
    const newFormData = createDefaultFormData(newData);
    Object.assign(formData, newFormData);
  }
}, { deep: true, immediate: true });

// Limpiar formulario cuando se cierra el modal
watch(() => props.isOpen, (isOpen) => {
  if (!isOpen) {
    setTimeout(() => {
      Object.assign(formData, createDefaultFormData());
      isSubmitting.value = false;
    }, 300);
  }
});

const closeModal = () => {
  emit('close');
};

const handleSubmit = async () => {
  isSubmitting.value = true;
  
  try {
    // Preparar datos para enviar
    const payload = {
      ...formData,
      education_levels: formData.education_levels
    };
    
    await router.post('/establishments', payload, {
      onSuccess: () => {
        emit('success');
        closeModal();
      },
      onError: (errors) => {
        console.error('Error al crear establecimiento:', errors);
        // Manejar errores aquí
      },
      onFinish: () => {
        isSubmitting.value = false;
      }
    });
  } catch (error) {
    console.error('Error al enviar formulario:', error);
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
.formkit-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

.formkit-input {
  @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border;
}

.formkit-input[type="textarea"] {
  @apply resize-y;
}

.formkit-input[multiple] {
  @apply h-24;
}
</style>
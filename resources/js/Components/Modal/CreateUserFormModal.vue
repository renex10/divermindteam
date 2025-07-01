<!-- resources/js/Components/Modal/CreateUserFormModal.vue -->
<template>
  <BaseModal :isOpen="isOpen" title="Crear Nuevo Usuario" @close="closeModal">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Nombres -->
      <FormKit
        type="text"
        v-model="formData.name"
        name="name"
        label="Nombres *"
        placeholder="Ingrese nombres"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Apellidos -->
      <FormKit
        type="text"
        v-model="formData.last_name"
        name="last_name"
        label="Apellidos *"
        placeholder="Ingrese apellidos"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Email -->
      <FormKit
        type="email"
        v-model="formData.email"
        name="email"
        label="Email *"
        placeholder="correo@ejemplo.com"
        validation="required|email"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- RUT -->
      <FormKit
        type="text"
        v-model="formData.rut"
        name="rut"
        label="RUT"
        placeholder="12.345.678-9"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Contraseña -->
      <FormKit
        type="password"
        v-model="formData.password"
        name="password"
        label="Contraseña *"
        placeholder="Mínimo 8 caracteres"
        validation="required|length:8"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Confirmar Contraseña -->
      <FormKit
        type="password"
        v-model="formData.password_confirmation"
        name="password_confirmation"
        label="Confirmar Contraseña *"
        placeholder="Repita la contraseña"
        :validation="[['required'], ['confirm', formData.password]]"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Rol -->
      <FormKit
        type="select"
        v-model="formData.role"
        name="role"
        label="Rol *"
        :options="roleOptions"
        placeholder="Seleccione un rol"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Teléfono -->
      <FormKit
        type="text"
        v-model="formData.phone"
        name="phone"
        label="Teléfono"
        placeholder="+56 9 1234 5678"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />
    </div>

    <!-- Usuario activo -->
    <div class="mt-6">
      <FormKit
        type="checkbox"
        v-model="formData.is_active"
        name="is_active"
        label="Usuario activo"
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
          {{ isSubmitting ? 'Guardando...' : 'Guardar Usuario' }}
        </button>
      </div>
    </template>
  </BaseModal>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import BaseModal from '@/Components/Modal/BaseModal.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  isOpen: Boolean,
  initialData: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['close', 'success']);

const isSubmitting = ref(false);

// Opciones de roles
const roleOptions = [
  { value: 'admin', label: 'Administrador' },
  { value: 'director', label: 'Director' },
  { value: 'teacher', label: 'Profesor' },
  { value: 'specialist', label: 'Especialista' },
  { value: 'guardian', label: 'Apoderado' }
];

// Función para crear datos por defecto
const createDefaultFormData = (initialData = {}) => {
  return {
    name: initialData.name || '',
    last_name: initialData.last_name || '',
    email: initialData.email || '',
    rut: initialData.rut || '',
    password: '',
    password_confirmation: '',
    role: initialData.role || 'teacher',
    phone: initialData.phone || '',
    is_active: initialData.is_active !== undefined ? initialData.is_active : true
  };
};

// Datos del formulario
const formData = reactive(createDefaultFormData(props.initialData));

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
    // Resetear formulario después de un pequeño delay para evitar que se vea el reset
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
    await router.post('/users', formData, {
      onSuccess: () => {
        emit('success');
        closeModal();
      },
      onError: (errors) => {
        console.error('Error al crear usuario:', errors);
        // Aquí podrías mostrar los errores en el formulario
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
</style>
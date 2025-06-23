<!-- resources/js/Components/StudentForm/BasicDataStep.vue -->
<template>
  <div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Campos para nuevo usuario -->
      <FormKit
        type="text"
        :value="formData.new_user.name"
        @input="updateNewUser('name', $event)"
        name="new_user[name]"
        label="Nombres *"
        placeholder="Ingrese nombres"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="text"
        :value="formData.new_user.last_name"
        @input="updateNewUser('last_name', $event)"
        name="new_user[last_name]"
        label="Apellidos *"
        placeholder="Apellidos"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Nuevo campo: RUT del estudiante -->
      <FormKit
        type="text"
        v-model="formData.student_rut"
        name="student_rut"
        label="RUT del Estudiante *"
        placeholder="12.345.678-9"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="email"
        :value="formData.new_user.email"
        @input="updateNewUser('email', $event)"
        name="new_user[email]"
        label="Email *"
        placeholder="correo@ejemplo.com"
        validation="required|email"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="password"
        :value="formData.new_user.password"
        @input="updateNewUser('password', $event)"
        name="new_user[password]"
        label="Contraseña *"
        validation="required|length:8"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Campos comunes -->
      <FormKit
        type="date"
        v-model="formData.birth_date"
        name="birth_date"
        label="Fecha de Nacimiento *"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="select"
        v-model="formData.course_id"
        name="course_id"
        label="Curso *"
        :options="courseOptions"
        placeholder="Seleccione curso"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="text"
        v-model="formData.diagnosis"
        name="diagnosis"
        label="Diagnóstico Preliminar"
        placeholder="Ej: TEL, TDAH, DEA..."
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="email"
        v-model="formData.guardian_email"
        name="guardian_email"
        label="Contacto de Apoderado"
        placeholder="correo@ejemplo.com"
        validation="email"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />
    </div>
  </div>
</template>

<script setup>
import { computed, watch } from 'vue';

const emit = defineEmits(['update:formData']);

const props = defineProps({
  formData: {
    type: Object,
    required: true
  },
  externalData: {
    type: Object,
    default: () => ({})
  }
});

// Función para actualizar campos anidados
const updateNewUser = (field, value) => {
  emit('update:formData', {
    ...props.formData,
    new_user: {
      ...props.formData.new_user,
      [field]: value
    }
  });
};

// Inicializar estructura para nuevo usuario
if (!props.formData.new_user) {
  emit('update:formData', {
    ...props.formData,
    new_user: {
      name: '',
      last_name: '',
      email: '',
      password: ''
    },
    student_rut: '' // Inicializar nuevo campo
  });
}

// Observar cambios en formData
watch(() => props.formData, (newValue) => {
  emit('update:formData', newValue);
}, { deep: true });

const courseOptions = computed(() => {
  if (!props.externalData?.courses) return [];
  
  if (!Array.isArray(props.externalData.courses)) {
    console.warn('courses no es un array:', props.externalData.courses);
    return [];
  }
  
  return props.externalData.courses
    .map(course => {
      if (!course.id || !course.name) return null;
      return { value: course.id, label: course.name };
    })
    .filter(Boolean);
});

console.log("BasicDataStep - Datos recibidos:", {
  formData: props.formData,
  externalData: props.externalData,
  courseOptions: courseOptions.value
});
</script>

<style scoped>
.formkit-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

.formkit-input {
  @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border;
}
</style>
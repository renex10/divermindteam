<template>
  <div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Campos para nuevo usuario -->
      <FormKit
        type="text"
        :modelValue="formData.new_user?.name"
        @update:modelValue="value => updateNewUser('name', value)"
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
        :modelValue="formData.new_user?.last_name"
        @update:modelValue="value => updateNewUser('last_name', value)"
        name="new_user[last_name]"
        label="Apellidos *"
        placeholder="Apellidos"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Campo RUT del estudiante -->
      <FormKit
        type="text"
        :modelValue="formData.student_rut"
        @update:modelValue="value => updateField('student_rut', value)"
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
        :modelValue="formData.new_user?.email"
        @update:modelValue="value => updateNewUser('email', value)"
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
        :modelValue="formData.new_user?.password || ''"
        @update:modelValue="value => updateNewUser('password', value)"
        name="new_user[password]"
        label="Contraseña *"
        placeholder="Dejar vacío para mantener actual"
        validation="length:8"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="date"
        :modelValue="formatDateForInput(formData.birth_date)"
        @update:modelValue="value => updateField('birth_date', value)"
        name="birth_date"
        label="Fecha de Nacimiento *"
        validation="required"
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <!-- Selector de curso corregido -->
      <FormKit
        type="select"
        :modelValue="formData.course_id ? String(formData.course_id) : ''"
        @update:modelValue="value => updateField('course_id', value ? Number(value) : null)"
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
        :modelValue="formData.diagnosis"
        @update:modelValue="value => updateField('diagnosis', value)"
        name="diagnosis"
        label="Diagnóstico Preliminar"
        placeholder="Ej: TEL, TDAH, DEA..."
        outer-class="mb-0"
        label-class="formkit-label"
        input-class="formkit-input"
      />

      <FormKit
        type="email"
        :modelValue="formData.guardian_email"
        @update:modelValue="value => updateField('guardian_email', value)"
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
import { computed, watch, onMounted } from 'vue';

const emit = defineEmits(['update:formData']);

const props = defineProps({
  formData: {
    type: Object,
    required: true,
    default: () => ({
      new_user: {
        name: '',
        last_name: '',
        email: '',
        password: ''
      },
      student_rut: '',
      birth_date: '',
      course_id: null,
      diagnosis: '',
      guardian_email: ''
    })
  },
  externalData: {
    type: Object,
    default: () => ({})
  }
});

// Función para inicializar campos faltantes
const initializeMissingFields = () => {
  const updates = {};
  
  // Inicializar new_user si no existe o está incompleto
  if (!props.formData.new_user || typeof props.formData.new_user !== 'object') {
    updates.new_user = {
      name: '',
      last_name: '',
      email: '',
      password: ''
    };
  } else {
    const defaultNewUser = { name: '', last_name: '', email: '', password: '' };
    const newUser = { ...defaultNewUser, ...props.formData.new_user };
    
    // Solo actualizar si hay cambios
    if (JSON.stringify(newUser) !== JSON.stringify(props.formData.new_user)) {
      updates.new_user = newUser;
    }
  }

  // Inicializar otros campos
  const defaultFields = {
    student_rut: '',
    birth_date: '',
    course_id: null,
    diagnosis: '',
    guardian_email: ''
  };

  Object.keys(defaultFields).forEach(key => {
    if (props.formData[key] === undefined) {
      updates[key] = defaultFields[key];
    }
  });

  // Emitir actualización si hay cambios
  if (Object.keys(updates).length > 0) {
    emit('update:formData', {
      ...props.formData,
      ...updates
    });
  }
};

// Inicializar campos faltantes al montar
onMounted(() => {
  console.log('formData recibido en BasicDataStep:', props.formData);
  console.log('externalData recibido:', props.externalData);
  initializeMissingFields();
});

const updateNewUser = (field, value) => {
  const newUser = {
    ...(props.formData.new_user || {}),
    [field]: value
  };

  emit('update:formData', {
    ...props.formData,
    new_user: newUser
  });
};

const updateField = (field, value) => {
  emit('update:formData', {
    ...props.formData,
    [field]: value
  });
};

// Función para formatear fecha
const formatDateForInput = (dateValue) => {
  if (!dateValue) return '';
  
  // Manejar diferentes formatos de fecha
  if (typeof dateValue === 'string') {
    // Si ya está en formato YYYY-MM-DD
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateValue)) {
      return dateValue;
    }
    
    // Si está en formato ISO
    if (dateValue.includes('T')) {
      return dateValue.split('T')[0];
    }
  }
  
  // Intentar convertir a fecha
  try {
    const date = new Date(dateValue);
    if (!isNaN(date.getTime())) {
      return date.toISOString().split('T')[0];
    }
  } catch (error) {
    console.warn('Error al formatear fecha:', dateValue, error);
  }
  
  return '';
};

// Generar opciones de cursos
const courseOptions = computed(() => {
  console.log('Calculando courseOptions, courses:', props.externalData?.courses);
  
  if (!props.externalData?.courses) return [];
  if (!Array.isArray(props.externalData.courses)) {
    console.warn('courses no es un array:', props.externalData.courses);
    return [];
  }

  const options = props.externalData.courses.map(course => {
    return {
      value: String(course.id),
      label: course.name
    };
  });
  
  console.log('Options generadas:', options);
  return options;
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
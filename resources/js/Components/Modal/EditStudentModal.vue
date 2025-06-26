<!-- resources/js/Components/Modal/EditStudentModal.vue -->
<!-- <template>
  <BaseModal :isOpen="isOpen" :title="title" @close="emit('close')"> -->
    <!-- Contenido del formulario -->
  <!--   <StudentForm
      :initial-data="initialData"
      :external-data="externalData"
      :is-loading="isLoading"
      @submit="handleSubmit"
    />
  </BaseModal>
</template>

<script setup>
import BaseModal from './BaseModal.vue'
import StudentForm from '../Students/StudentForm.vue'

const props = defineProps({
  isOpen: Boolean,
  title: {
    type: String,
    default: 'Editar estudiante'
  },
  initialData: {
    type: Object,
    required: true
  },
  externalData: {
    type: Object,
    required: true
  },
  isLoading: Boolean
})

const emit = defineEmits(['close', 'submit'])

const handleSubmit = (formData) => {
  emit('submit', formData)
}
</script> -->
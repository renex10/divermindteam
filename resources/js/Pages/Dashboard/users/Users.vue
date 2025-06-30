<script setup>
import { defineProps, ref } from 'vue' 
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import UserTable from '@/Components/Table/UserTable.vue'
import ButtonAdd from '@/Components/Bottom/ButtonAdd.vue'
import BaseModal from '@/Components/Modal/BaseModal.vue'  

const props = defineProps({
  users: {
    type: Array,
    required: true
  }
})

// Estado modal para crear usuario
const isUserModalOpen = ref(false)
const openUserModal = () => isUserModalOpen.value = true
const closeUserModal = () => isUserModalOpen.value = false

// Estado modal para crear escuela
const isSchoolModalOpen = ref(false)
const openSchoolModal = () => isSchoolModalOpen.value = true
const closeSchoolModal = () => isSchoolModalOpen.value = false
</script>

<template>
  <DashboardLayout title="Gestión de Usuarios">
    <template #header>
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
        <h2 class="text-xl font-semibold text-gray-800">Gestión de Usuarios</h2>
        <div class="flex gap-2">
          <ButtonAdd label="Agregar Usuario" @openModal="openUserModal" />
          <ButtonAdd label="Agregar Escuela" @openModal="openSchoolModal" />
        </div>
      </div>
    </template>

    <!-- Modal: Crear Usuario -->
    <BaseModal 
      :isOpen="isUserModalOpen" 
      title="Crear Nuevo Usuario"
      @close="closeUserModal"
    >
      <div class="p-4">
        <p>Formulario de creación de usuario irá aquí</p>
      </div>

      <template #footer>
        <div class="flex justify-end space-x-3">
          <button 
            @click="closeUserModal"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
          >
            Cancelar
          </button>
          <button 
            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
          >
            Guardar
          </button>
        </div>
      </template>
    </BaseModal>

    <!-- Modal: Crear Escuela -->
    <BaseModal 
      :isOpen="isSchoolModalOpen" 
      title="Crear Nueva Escuela"
      @close="closeSchoolModal"
    >
      <div class="p-4">
        <p>Formulario de creación de escuela irá aquí</p>
      </div>

      <template #footer>
        <div class="flex justify-end space-x-3">
          <button 
            @click="closeSchoolModal"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
          >
            Cancelar
          </button>
          <button 
            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
          >
            Guardar
          </button>
        </div>
      </template>
    </BaseModal>

    <!-- Tabla de usuarios -->
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <UserTable :users="users" />
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

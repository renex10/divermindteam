// resources/js/Pages/Dashboard/users/Users.vue - SCRIPT SECTION
<script setup>
/* resources\js\Pages\Dashboard\users\Users.vue */
import { defineProps, ref } from 'vue' 
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import UserTable from '@/Components/Table/UserTable.vue'
import ButtonAdd from '@/Components/Bottom/ButtonAdd.vue'
import CreateUserFormModal from '@/Components/Modal/CreateUserFormModal.vue'
import CreateEstablishmentModal from '@/Components/Modal/CreateEstablishmentModal.vue'

const props = defineProps({
  users: {
    type: Array,
    required: true
  },
  regions: {     // ✅ Agregamos regions como prop
    type: Array,
    default: () => []
  },
  communes: {
    type: Array,
    default: () => []
  }
})

// DEBUG: Verificar datos recibidos
console.log('Props recibidos en Users.vue:');
console.log('- Users:', props.users?.length || 0);
console.log('- Regions:', props.regions?.length || 0, props.regions);
console.log('- Communes:', props.communes?.length || 0, props.communes);

// Estado modal para crear usuario
const isUserModalOpen = ref(false)
const openUserModal = () => isUserModalOpen.value = true
const closeUserModal = () => isUserModalOpen.value = false

// Estado modal para crear establecimiento
const isEstablishmentModalOpen = ref(false)
const openEstablishmentModal = () => isEstablishmentModalOpen.value = true
const closeEstablishmentModal = () => isEstablishmentModalOpen.value = false

// Handlers para éxito
const handleUserCreated = () => {
  console.log('Usuario creado exitosamente')
  // Aquí podrías mostrar una notificación o recargar la tabla
}

const handleEstablishmentCreated = () => {
  console.log('Establecimiento creado exitosamente')
  // Aquí podrías mostrar una notificación o recargar la tabla
}
</script>

<template>
  <DashboardLayout title="Gestión de Usuarios">
    <template #header>
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
        <h2 class="text-xl font-semibold text-gray-800">Gestión de Usuarios</h2>
        <div class="flex gap-2">
          <ButtonAdd label="Agregar Usuario" @openModal="openUserModal" />
          <ButtonAdd label="Agregar Establecimiento" @openModal="openEstablishmentModal" />
        </div>
      </div>
    </template>

    <!-- Modal: Crear Usuario -->
    <CreateUserFormModal 
      :isOpen="isUserModalOpen"
      @close="closeUserModal"
      @success="handleUserCreated"
    />

    <!-- Modal: Crear Establecimiento -->
    <CreateEstablishmentModal 
      :isOpen="isEstablishmentModalOpen"
      :regions="regions"    
      :communes="communes"  
      @close="closeEstablishmentModal"
      @success="handleEstablishmentCreated"
    />

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
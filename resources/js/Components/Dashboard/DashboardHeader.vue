<template>
  <header class="bg-white shadow-sm border-b border-gray-200 px-4 py-4 sm:px-6">
    <div class="flex items-center justify-between">
      <!-- Left Section - Title and Description -->
      <div class="flex-1 min-w-0">
        <div class="flex items-center">
          <!-- Mobile Sidebar Toggle (visible solo en móvil) -->
          <button
            @click="$emit('toggle-sidebar')"
            class="lg:hidden mr-3 p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
            aria-label="Abrir menú de navegación"
          >
            <Bars3Icon class="h-6 w-6" />
          </button>
          
          <!-- Page Title and Description -->
          <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-semibold text-gray-900 truncate">
              {{ title }}
            </h1>
            <p v-if="description" class="mt-1 text-sm text-gray-500 truncate">
              {{ description }}
            </p>
          </div>
        </div>
      </div>

      <!-- Right Section - Actions and User Menu -->
      <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <button
          type="button"
          class="relative p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
          aria-label="Ver notificaciones"
        >
          <BellIcon class="h-6 w-6" />
          <!-- Notification Badge -->
          <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
        </button>

        <!-- Search (opcional) -->
        <div class="hidden md:block">
          <div class="relative">
            <MagnifyingGlassIcon class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
            <input
              type="text"
              placeholder="Buscar..."
              class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>
        </div>

        <!-- User Menu -->
        <div class="relative" ref="userMenuRef">
          <button
            @click="toggleUserMenu"
            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
            :aria-expanded="userMenuOpen"
          >
            <img
              class="h-8 w-8 rounded-full object-cover"
              src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
              alt="Usuario"
            />
            <div class="hidden md:block text-left">
              <div class="text-sm font-medium text-gray-900">John Doe</div>
              <div class="text-xs text-gray-500">Administrador</div>
            </div>
            <ChevronDownIcon 
              :class="[
                'h-4 w-4 text-gray-400 transition-transform duration-200',
                userMenuOpen ? 'rotate-180' : ''
              ]" 
            />
          </button>

          <!-- User Dropdown Menu -->
          <Transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
          >
            <div
              v-if="userMenuOpen"
              class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
              role="menu"
            >
              <div class="py-1">
                <a
                  href="/profile"
                  class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  role="menuitem"
                >
                  <UserCircleIcon class="mr-3 h-5 w-5 text-gray-400" />
                  Mi Perfil
                </a>
                <a
                  href="/settings"
                  class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  role="menuitem"
                >
                  <CogIcon class="mr-3 h-5 w-5 text-gray-400" />
                  Configuración
                </a>
                <hr class="my-1" />
                <button
                  @click="handleLogout"
                  class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  role="menuitem"
                >
                  <ArrowRightOnRectangleIcon class="mr-3 h-5 w-5 text-gray-400" />
                  Cerrar Sesión
                </button>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import {
  Bars3Icon,
  BellIcon,
  MagnifyingGlassIcon,
  ChevronDownIcon,
  UserCircleIcon,
  CogIcon,
  ArrowRightOnRectangleIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  title: {
    type: String,
    default: 'Dashboard'
  },
  description: {
    type: String,
    default: ''
  },
  isSidebarCollapsed: {
    type: Boolean,
    default: false
  }
})

// Emits
const emit = defineEmits(['toggle-sidebar', 'logout'])

// State
const userMenuOpen = ref(false)
const userMenuRef = ref(null)

// Methods
const toggleUserMenu = () => {
  userMenuOpen.value = !userMenuOpen.value
}

const handleLogout = () => {
  emit('logout')
  userMenuOpen.value = false
}

const handleClickOutside = (event) => {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    userMenuOpen.value = false
  }
}

// Lifecycle
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>
<template>
  <!-- Sidebar Container -->
  <aside
    :class="[
      'bg-gray-800 text-white shadow-xl transition-all duration-300 ease-in-out',
      'flex flex-col h-full',
      // Desktop: Always visible, resizable width
      'hidden lg:flex',
      isCollapsed ? 'lg:w-16' : 'lg:w-64',
      // Mobile: Fixed overlay when open
      isMobileOpen ? 'fixed inset-y-0 left-0 z-50 w-64 flex lg:relative lg:translate-x-0' : ''
    ]"
    role="navigation"
    aria-label="Sidebar navigation"
  >
    <!-- Header Section -->
    <div class="flex items-center justify-between p-4 border-b border-gray-700 flex-shrink-0">
      <div v-if="!isCollapsed" class="flex items-center space-x-3 min-w-0">
        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
          <span class="text-white font-bold text-sm">A</span>
        </div>
        <h1 class="text-lg font-semibold truncate">Admin Panel</h1>
      </div>
      
      <button
        @click="toggleCollapse"
        class="p-2 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors flex-shrink-0"
        :aria-label="isCollapsed ? 'Expandir sidebar' : 'Colapsar sidebar'"
      >
        <ChevronLeftIcon 
          :class="[
            'w-5 h-5 transition-transform duration-300',
            isCollapsed ? 'rotate-180' : ''
          ]" 
        />
      </button>
    </div>

    <!-- Navigation Section - Scrolleable -->
    <nav class="flex-1 overflow-y-auto py-4" role="menubar">
      <ul class="space-y-1 px-3">
        <SidebarItem
          v-for="item in menuItems"
          :key="item.id"
          :item="item"
          :is-collapsed="isCollapsed"
          :active-item="activeItem"
          @item-click="handleItemClick"
        />
      </ul>
    </nav>

    <!-- Footer Section -->
    <div class="border-t border-gray-700 p-4 flex-shrink-0">
      <SidebarItem
        :item="userMenuItem"
        :is-collapsed="isCollapsed"
        :active-item="activeItem"
        @item-click="handleItemClick"
      />
    </div>
  </aside>

  <!-- Mobile Overlay -->
  <div
    v-if="isMobileOpen"
    @click="closeMobile"
    class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
    aria-hidden="true"
  />
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { 
  ChevronLeftIcon,
  HomeIcon,
  UsersIcon,
  ChartBarIcon,
  CogIcon,
  DocumentTextIcon,
  ShoppingBagIcon,
  BellIcon,
  UserCircleIcon
} from '@heroicons/vue/24/outline'
import SidebarItem from './SidebarItem.vue'

// Props
const props = defineProps({
  defaultCollapsed: {
    type: Boolean,
    default: false
  },
  mobileBreakpoint: {
    type: Number,
    default: 1024
  }
})

// Emits
const emit = defineEmits(['toggle', 'item-click'])

// Reactive state
const isCollapsed = ref(props.defaultCollapsed)
const isMobileOpen = ref(false)
const activeItem = ref('dashboard')
const screenWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1024)

// Computed
const isDesktop = computed(() => screenWidth.value >= props.mobileBreakpoint)

// Menu configuration
const menuItems = ref([
  {
    id: 'dashboard',
    label: 'Dashboard',
    icon: HomeIcon,
    href: '/dashboard',
    badge: null
  },
  {
    id: 'estudiantes',
    label: 'Estudiantes',
    icon: UsersIcon,
    href: '/estudiantes',
    badge: { text: '12', color: 'bg-blue-600' }
  },
  {
    id: 'analytics',
    label: 'Analíticas',
    icon: ChartBarIcon,
    children: [
      {
        id: 'reports',
        label: 'Reportes',
        href: '/analytics/reports'
      },
      {
        id: 'metrics',
        label: 'Métricas',
        href: '/analytics/metrics'
      }
    ]
  },
  {
    id: 'products',
    label: 'Productos',
    icon: ShoppingBagIcon,
    href: '/products',
    badge: null
  },
  {
    id: 'documents',
    label: 'Documentos',
    icon: DocumentTextIcon,
    href: '/documents',
    badge: null
  },
  {
    id: 'notifications',
    label: 'Notificaciones',
    icon: BellIcon,
    href: '/notifications',
    badge: { text: '3', color: 'bg-red-600' }
  },
  {
    id: 'settings',
    label: 'Configuración',
    icon: CogIcon,
    href: '/settings',
    badge: null
  }
])

const userMenuItem = ref({
  id: 'profile',
  label: 'Mi Perfil',
  icon: UserCircleIcon,
  href: '/profile',
  badge: null
})

// Methods
const toggleCollapse = () => {
  isCollapsed.value = !isCollapsed.value
  emit('toggle', isCollapsed.value)
}

const openMobile = () => {
  isMobileOpen.value = true
}

const closeMobile = () => {
  isMobileOpen.value = false
}

const handleItemClick = (item) => {
  activeItem.value = item.id
  emit('item-click', item)
  
  // Close mobile sidebar after selection
  if (!isDesktop.value) {
    closeMobile()
  }
}

const handleResize = () => {
  screenWidth.value = window.innerWidth
  
  // Auto-close mobile sidebar on desktop resize
  if (isDesktop.value) {
    isMobileOpen.value = false
  }
}

// Lifecycle
onMounted(() => {
  if (typeof window !== 'undefined') {
    window.addEventListener('resize', handleResize)
  }
})

onUnmounted(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('resize', handleResize)
  }
})

// Expose methods for parent components
defineExpose({
  toggleCollapse,
  openMobile,
  closeMobile,
  isCollapsed,
  isMobileOpen
})
</script>
<template>
  <header class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
    <div class="px-4 sm:px-6 py-4">
      <div class="flex items-center justify-between">
        <!-- Title Section -->
        <div class="min-w-0 flex-1">
          <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">
            {{ title }}
          </h1>
          <p class="text-sm sm:text-base text-gray-600 mt-1 truncate">
            {{ description }}
          </p>
        </div>
        
        <!-- Header Actions -->
        <div class="flex items-center space-x-2 sm:space-x-4 ml-4">
          <!-- Notifications Button -->
          <button 
            class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
            aria-label="Notificaciones"
          >
            <BellIcon class="w-5 h-5 sm:w-6 sm:h-6" />
            <!-- Notification Badge -->
            <span class="sr-only">3 notificaciones sin leer</span>
            <div class="absolute -mt-1 -mr-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></div>
          </button>
          
          <!-- User Menu -->
          <div class="relative">
            <button
              @click="toggleUserMenu"
              class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
              :aria-expanded="isUserMenuOpen"
              aria-haspopup="true"
            >
              <!-- User Avatar -->
              <div v-if="$page.props.jetstream.managesProfilePhotos" class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                <img 
                  class="w-full h-full object-cover" 
                  :src="$page.props.auth.user.profile_photo_url" 
                  :alt="$page.props.auth.user.name"
                >
              </div>
              <div v-else class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white text-sm font-medium">
                  {{ userInitials }}
                </span>
              </div>
              
              <div class="hidden sm:block text-left min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                  {{ $page.props.auth.user.name }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                  {{ $page.props.auth.user.email }}
                </p>
              </div>
              <ChevronDownIcon 
                :class="[
                  'w-4 h-4 text-gray-400 transition-transform duration-200',
                  isUserMenuOpen ? 'rotate-180' : ''
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
                v-if="isUserMenuOpen"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                role="menu"
                aria-orientation="vertical"
              >
                <!-- Profile Link -->
                <Link
                  :href="route('profile.show')"
                  @click="isUserMenuOpen = false"
                  class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                  role="menuitem"
                >
                  <UserCircleIcon class="w-4 h-4 mr-3 text-gray-400" />
                  Perfil
                </Link>

                <!-- API Tokens (if enabled) -->
                <Link
                  v-if="$page.props.jetstream.hasApiFeatures"
                  :href="route('api-tokens.index')"
                  @click="isUserMenuOpen = false"
                  class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                  role="menuitem"
                >
                  <CogIcon class="w-4 h-4 mr-3 text-gray-400" />
                  API Tokens
                </Link>

                <!-- Team Management (if teams are enabled) -->
                <template v-if="$page.props.jetstream.hasTeamFeatures">
                  <hr class="my-1 border-gray-200">
                  
                  <div class="px-4 py-2 text-xs text-gray-400">
                    Gestión de Equipos
                  </div>

                  <!-- Team Settings -->
                  <Link
                    :href="route('teams.show', $page.props.auth.user.current_team)"
                    @click="isUserMenuOpen = false"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                    role="menuitem"
                  >
                    <CogIcon class="w-4 h-4 mr-3 text-gray-400" />
                    Configuración del Equipo
                  </Link>

                  <!-- Create New Team -->
                  <Link
                    v-if="$page.props.jetstream.canCreateTeams"
                    :href="route('teams.create')"
                    @click="isUserMenuOpen = false"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                    role="menuitem"
                  >
                    <PlusIcon class="w-4 h-4 mr-3 text-gray-400" />
                    Crear Nuevo Equipo
                  </Link>

                  <!-- Team Switcher -->
                  <template v-if="$page.props.auth.user.all_teams.length > 1">
                    <hr class="my-1 border-gray-200">
                    
                    <div class="px-4 py-2 text-xs text-gray-400">
                      Cambiar Equipo
                    </div>

                    <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                      <form @submit.prevent="switchToTeam(team)">
                        <button
                          type="submit"
                          class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors text-left"
                          role="menuitem"
                          @click="isUserMenuOpen = false"
                        >
                          <CheckCircleIcon 
                            v-if="team.id == $page.props.auth.user.current_team_id" 
                            class="w-4 h-4 mr-3 text-green-400" 
                          />
                          <div v-else class="w-4 h-4 mr-3"></div>
                          {{ team.name }}
                        </button>
                      </form>
                    </template>
                  </template>
                </template>
                
                <hr class="my-1 border-gray-200">
                
                <!-- Logout Button -->
                <form @submit.prevent="logout">
                  <button
                    type="submit"
                    class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50 transition-colors text-left"
                    role="menuitem"
                  >
                    <ArrowRightOnRectangleIcon class="w-4 h-4 mr-3 text-red-400" />
                    Cerrar Sesión
                  </button>
                </form>
              </div>
            </Transition>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { 
  BellIcon, 
  ChevronDownIcon,
  UserCircleIcon,
  CogIcon,
  ArrowRightOnRectangleIcon,
  PlusIcon,
  CheckCircleIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  title: {
    type: String,
    required: true
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

// State
const isUserMenuOpen = ref(false)

// Computed
const userInitials = computed(() => {
  const name = $page.props.auth.user.name
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .substring(0, 2)
})

// Methods
const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value
}

const logout = () => {
  router.post(route('logout'))
}

const switchToTeam = (team) => {
  router.put(route('current-team.update'), {
    team_id: team.id,
  }, {
    preserveState: false,
  })
}

const handleClickOutside = (event) => {
  const userMenu = event.target.closest('[aria-haspopup="true"]')
  if (!userMenu && isUserMenuOpen.value) {
    isUserMenuOpen.value = false
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
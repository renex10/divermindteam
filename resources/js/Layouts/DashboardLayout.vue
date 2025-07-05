<script setup>
/* la ruta del archivo es resources\js\Layouts\DashboardLayout.vue */
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
// Importa tus componentes personalizados
import DashboardSidebar from '@/Components/Dashboard/DashboardSidebar.vue';
import DashboardHeader from '@/Components/Dashboard/DashboardHeader.vue';
import MobileMenuButton from '@/Components/Dashboard/MobileMenuButton.vue';
// Importa el preloader
import PreloaderDashboard from '@/Components/Dashboard/PreloaderDashboard.vue'; 

// Estados para controlar el preloader
const isLoading = ref(false); // Controla si hay una carga en curso
const showLoader = ref(false); // Controla si se debe mostrar visualmente el preloader
let loaderTimeout = null; // Para manejar el tiempo mínimo de visualización

const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    },
    description: {
        type: String,
        default: 'Bienvenido al panel de administración'
    }
});

// Estados reactivos para el layout
const sidebarRef = ref(null);
const isSidebarCollapsed = ref(false);
const activeMenuItem = ref('dashboard');
const screenWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1024);

// Computed
const isDesktop = computed(() => screenWidth.value >= 1024);

const currentPageTitle = computed(() => {
    const pageMap = {
        'dashboard': 'Dashboard',
        'users': 'Gestión de Usuarios',
        'analytics': 'Analíticas',
        'reports': 'Reportes',
        'metrics': 'Métricas',
        'products': 'Gestión de Productos',
        'documents': 'Documentos',
        'notifications': 'Notificaciones',
        'settings': 'Configuración',
        'profile': 'Mi Perfil'
    };
    return pageMap[activeMenuItem.value] || props.title;
});

const currentPageDescription = computed(() => {
    const descMap = {
        'dashboard': 'Vista general del sistema y métricas principales',
        'users': 'Administra usuarios, roles y permisos',
        'analytics': 'Analiza el rendimiento y métricas del sistema',
        'reports': 'Genera y visualiza reportes detallados',
        'metrics': 'Monitorea métricas clave de rendimiento',
        'products': 'Gestiona el catálogo de productos',
        'documents': 'Organiza y administra documentos',
        'notifications': 'Centro de notificaciones y alertas',
        'settings': 'Configuración general del sistema',
        'profile': 'Gestiona tu perfil y preferencias'
    };
    return descMap[activeMenuItem.value] || props.description;
});

// Methods
const handleSidebarToggle = (collapsed) => {
    isSidebarCollapsed.value = collapsed;
};

const handleItemClick = (item) => {
    activeMenuItem.value = item.id;
    console.log('Navegando a:', item.label, item.href);
    
    // Integración con Inertia.js router
    if (item.href) {
        router.visit(item.href);
    }
};

const openMobileSidebar = () => {
    if (sidebarRef.value) {
        sidebarRef.value.openMobile();
    }
};

const logout = () => {
    router.post(route('logout'));
};

const handleResize = () => {
    screenWidth.value = window.innerWidth;
};

// Lifecycle - SOLUCIÓN MEJORADA para el preloader
onMounted(() => {
    // 🎯 PRELOADER INTELIGENTE: Solo mostrar en navegaciones importantes
    router.on('start', (event) => {
        // Limpiar timeout si existe
        if (loaderTimeout) clearTimeout(loaderTimeout);
        
        // 🚫 NO mostrar preloader en estas situaciones:
        const shouldSkipLoader = (
            // 1. Navegaciones que preservan estado (búsquedas, filtros)
            event.detail.visit.preserveState ||
            
            // 2. Navegaciones que solo actualizan ciertos componentes
            (event.detail.visit.only && event.detail.visit.only.length > 0) ||
            
            // 3. Navegaciones que preservan scroll (generalmente filtros/búsquedas)
            event.detail.visit.preserveScroll ||
            
            // 4. Navegaciones con method GET que incluyen parámetros de búsqueda
            (event.detail.visit.method === 'get' && 
             event.detail.visit.url.includes('search=')) ||
            
            // 5. Navegaciones con method GET que incluyen parámetros de filtro
            (event.detail.visit.method === 'get' && 
             event.detail.visit.url.includes('filter=')) ||
            
            // 6. Navegaciones que son del mismo componente (refrescos de datos)
            (event.detail.visit.url === window.location.pathname + window.location.search)
        );
        
        // Solo mostrar preloader si NO es una de las situaciones excluidas
        if (!shouldSkipLoader) {
            console.log('🔄 Mostrando preloader para navegación:', event.detail.visit.url);
            isLoading.value = true;
            showLoader.value = true;
        } else {
            console.log('⚡ Navegación rápida, omitiendo preloader:', event.detail.visit.url);
        }
    });

    // Ocultar preloader al completar navegación
    router.on('finish', (event) => {
        // Solo procesar si el preloader estaba visible
        if (isLoading.value && showLoader.value) {
            // Establecer un tiempo mínimo de visualización del preloader (300ms)
            loaderTimeout = setTimeout(() => {
                showLoader.value = false; // Inicia la animación de salida
                
                // Después de la animación, ocultar completamente
                setTimeout(() => {
                    isLoading.value = false;
                }, 300); // Tiempo que dura la animación de salida
            }, 300); // Tiempo mínimo reducido para mejor UX
        } else {
            // Asegurar que el preloader esté oculto
            isLoading.value = false;
            showLoader.value = false;
        }
    });

    // Manejar errores de navegación
    router.on('error', () => {
        // Ocultar preloader inmediatamente en caso de error
        if (loaderTimeout) clearTimeout(loaderTimeout);
        showLoader.value = false;
        isLoading.value = false;
    });

    if (typeof window !== 'undefined') {
        window.addEventListener('resize', handleResize);
    }
});

onUnmounted(() => {
    if (typeof window !== 'undefined') {
        window.removeEventListener('resize', handleResize);
    }

    // Limpiar listeners y timeout
    router.off('start');
    router.off('finish');
    if (loaderTimeout) clearTimeout(loaderTimeout);
});

// Expose for parent components
defineExpose({
    openMobileSidebar,
    currentPageTitle,
    currentPageDescription,
    activeMenuItem
});
</script>

<template>
    <div class="flex h-screen bg-gray-100 overflow-hidden relative">
        <!-- Preloader (se muestra durante transiciones) -->
        <Transition name="fade">
            <PreloaderDashboard v-if="showLoader" />
        </Transition>

        <!-- Head para SEO y título -->
        <Head :title="currentPageTitle" />
        
        <!-- Sidebar Component -->
        <DashboardSidebar 
            ref="sidebarRef"
            :default-collapsed="false"
            @toggle="handleSidebarToggle"
            @item-click="handleItemClick"
        />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Menu Button - Solo visible en móvil -->
            <MobileMenuButton 
                @click="openMobileSidebar"
                class="lg:hidden"
            />

            <!-- Header - Fijo en la parte superior -->
            <DashboardHeader 
                :title="currentPageTitle"
                :description="currentPageDescription"
                :is-sidebar-collapsed="isSidebarCollapsed"
                @toggle-sidebar="openMobileSidebar"
                @logout="logout"
                class="flex-shrink-0"
            />
            
            <!-- Page Header (slot) - Si existe -->
            <header v-if="$slots.header" class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
                <div class="px-4 py-4 sm:px-6">
                    <slot name="header" />
                </div>
            </header>
            
            <!-- Page Content - Scrolleable -->
            <main class="flex-1 overflow-y-auto">
                <div class="p-4 sm:p-6">
                    <div class="max-w-7xl mx-auto">
                        <!-- Slot para contenido específico de cada página -->
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Animación mejorada para el preloader */
.fade-enter-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
  animation: fadeIn 0.3s;
}

.fade-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
  animation: fadeOut 0.5s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes fadeOut {
  from {
    opacity: 1;
    transform: scale(1);
  }
  to {
    opacity: 0;
    transform: scale(0.95);
  }
}
</style>
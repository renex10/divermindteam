<script setup>
/* la ruta del archivo es resources\js\Layouts\DashboardLayout.vue */
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
// Importa tus componentes personalizados
import DashboardSidebar from '@/Components/Dashboard/DashboardSidebar.vue';
import DashboardHeader from '@/Components/Dashboard/DashboardHeader.vue';
import MobileMenuButton from '@/Components/Dashboard/MobileMenuButton.vue';
// Importa el preloader
import PreloaderDashboard from '@/Components/Dashboard/PreloaderDashboard.vue'; 

// Estados para controlar el preloader
const isLoading = ref(false);
const showLoader = ref(false);
let loaderTimeout = null;

const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    },
    description: {
        type: String,
        default: 'Bienvenido al panel de administración'
    },
    // Nuevo prop para controlar si se muestra el preloader
    showPreloader: {
        type: Boolean,
        default: true
    }
});

// Estados reactivos para el layout
const sidebarRef = ref(null);
const isSidebarCollapsed = ref(() => {
    // Recuperar estado del localStorage si está disponible
    if (typeof window !== 'undefined') {
        const saved = localStorage.getItem('sidebar-collapsed');
        return saved ? JSON.parse(saved) : false;
    }
    return false;
});
const activeMenuItem = ref('dashboard');
const screenWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1024);

// Computed
const isDesktop = computed(() => screenWidth.value >= 1024);
const isMobile = computed(() => screenWidth.value < 768);
const isTablet = computed(() => screenWidth.value >= 768 && screenWidth.value < 1024);

// Configuración de páginas mejorada
const PAGE_CONFIG = {
    'dashboard': {
        title: 'Dashboard',
        description: 'Vista general del sistema y métricas principales',
        icon: 'dashboard'
    },
    'users': {
        title: 'Gestión de Usuarios',
        description: 'Administra usuarios, roles y permisos',
        icon: 'users'
    },
    'analytics': {
        title: 'Analíticas',
        description: 'Analiza el rendimiento y métricas del sistema',
        icon: 'analytics'
    },
    'reports': {
        title: 'Reportes',
        description: 'Genera y visualiza reportes detallados',
        icon: 'reports'
    },
    'metrics': {
        title: 'Métricas',
        description: 'Monitorea métricas clave de rendimiento',
        icon: 'metrics'
    },
    'products': {
        title: 'Gestión de Productos',
        description: 'Gestiona el catálogo de productos',
        icon: 'products'
    },
    'documents': {
        title: 'Documentos',
        description: 'Organiza y administra documentos',
        icon: 'documents'
    },
    'notifications': {
        title: 'Notificaciones',
        description: 'Centro de notificaciones y alertas',
        icon: 'notifications'
    },
    'settings': {
        title: 'Configuración',
        description: 'Configuración general del sistema',
        icon: 'settings'
    },
    'profile': {
        title: 'Mi Perfil',
        description: 'Gestiona tu perfil y preferencias',
        icon: 'profile'
    }
};

const currentPageTitle = computed(() => {
    return PAGE_CONFIG[activeMenuItem.value]?.title || props.title;
});

const currentPageDescription = computed(() => {
    return PAGE_CONFIG[activeMenuItem.value]?.description || props.description;
});

const currentPageIcon = computed(() => {
    return PAGE_CONFIG[activeMenuItem.value]?.icon || 'dashboard';
});

// Methods
const handleSidebarToggle = (collapsed) => {
    isSidebarCollapsed.value = collapsed;
    // Guardar estado en localStorage
    if (typeof window !== 'undefined') {
        localStorage.setItem('sidebar-collapsed', JSON.stringify(collapsed));
    }
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
    // Mostrar confirmación antes de cerrar sesión
    if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        router.post(route('logout'));
    }
};

const handleResize = () => {
    screenWidth.value = window.innerWidth;
    
    // Auto-colapsar sidebar en pantallas pequeñas
    if (screenWidth.value < 768 && !isSidebarCollapsed.value) {
        handleSidebarToggle(true);
    }
};

// Función para limpiar timeouts
const clearLoaderTimeout = () => {
    if (loaderTimeout) {
        clearTimeout(loaderTimeout);
        loaderTimeout = null;
    }
};

// Función mejorada para manejar el preloader
const showPreloaderWithMinTime = () => {
    if (!props.showPreloader) return;
    
    clearLoaderTimeout();
    isLoading.value = true;
    showLoader.value = true;
};

const hidePreloaderWithDelay = () => {
    if (!props.showPreloader) return;
    
    loaderTimeout = setTimeout(() => {
        showLoader.value = false;
        
        setTimeout(() => {
            isLoading.value = false;
        }, 300); // Tiempo de animación de salida
    }, 500); // Tiempo mínimo de visualización
};

// Detectar cambios en el estado del sidebar para responsive
watch(screenWidth, (newWidth) => {
    // En móvil, siempre colapsar
    if (newWidth < 768) {
        handleSidebarToggle(true);
    }
    // En desktop, restaurar estado guardado
    else if (newWidth >= 1024) {
        const saved = localStorage.getItem('sidebar-collapsed');
        if (saved) {
            handleSidebarToggle(JSON.parse(saved));
        }
    }
});

// Lifecycle
onMounted(() => {
    // Configurar listeners de Inertia.js
    const startHandler = () => showPreloaderWithMinTime();
    const finishHandler = () => hidePreloaderWithDelay();
    const errorHandler = () => hidePreloaderWithDelay();
    
    router.on('start', startHandler);
    router.on('finish', finishHandler);
    router.on('error', errorHandler);
    
    // Listener para resize
    if (typeof window !== 'undefined') {
        window.addEventListener('resize', handleResize);
        
        // Aplicar estado inicial del sidebar
        handleResize();
    }
    
    // Almacenar referencias para cleanup
    window._dashboardListeners = {
        start: startHandler,
        finish: finishHandler,
        error: errorHandler
    };
});

onUnmounted(() => {
    // Remover listeners de window
    if (typeof window !== 'undefined') {
        window.removeEventListener('resize', handleResize);
        
        // Limpiar listeners de Inertia.js
        if (window._dashboardListeners) {
            router.off('start', window._dashboardListeners.start);
            router.off('finish', window._dashboardListeners.finish);
            router.off('error', window._dashboardListeners.error);
            delete window._dashboardListeners;
        }
    }
    
    // Limpiar timeouts
    clearLoaderTimeout();
});

// Expose for parent components
defineExpose({
    openMobileSidebar,
    currentPageTitle,
    currentPageDescription,
    currentPageIcon,
    activeMenuItem,
    isSidebarCollapsed,
    isDesktop,
    isMobile,
    isTablet
});
</script>

<template>
    <div class="flex h-screen bg-gray-50 overflow-hidden relative">
        <!-- Preloader (se muestra durante transiciones) -->
        <Transition name="fade">
            <PreloaderDashboard v-if="showLoader" />
        </Transition>

        <!-- Head para SEO y título -->
        <Head :title="currentPageTitle" />
        
        <!-- Sidebar Component -->
        <DashboardSidebar 
            ref="sidebarRef"
            :default-collapsed="isSidebarCollapsed"
            @toggle="handleSidebarToggle"
            @item-click="handleItemClick"
        />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Menu Button - Solo visible en móvil -->
            <MobileMenuButton 
                @click="openMobileSidebar"
                class="lg:hidden fixed top-4 left-4 z-50 bg-white shadow-lg rounded-lg"
                :class="{ 'ml-64': !isSidebarCollapsed && isDesktop }"
            />

            <!-- Header - Fijo en la parte superior -->
            <DashboardHeader 
                :title="currentPageTitle"
                :description="currentPageDescription"
                :icon="currentPageIcon"
                :is-sidebar-collapsed="isSidebarCollapsed"
                :is-mobile="isMobile"
                @toggle-sidebar="openMobileSidebar"
                @logout="logout"
                class="flex-shrink-0 transition-all duration-300"
            />
            
            <!-- Page Header (slot) - Si existe -->
            <header v-if="$slots.header" class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
                <div class="px-4 py-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
            
            <!-- Page Content - Scrolleable -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-4 sm:p-6 lg:p-8">
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
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.fade-leave-active {
    transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: scale(0.95) translateY(-10px);
}

.fade-enter-to,
.fade-leave-from {
    opacity: 1;
    transform: scale(1) translateY(0);
}

/* Transiciones suaves para el layout */
.transition-all {
    transition: all 0.3s ease;
}

/* Estilos para mejorar la experiencia visual */
main {
    scroll-behavior: smooth;
}

/* Scrollbar personalizada */
main::-webkit-scrollbar {
    width: 6px;
}

main::-webkit-scrollbar-track {
    background: #f1f5f9;
}

main::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

main::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Mejoras para accesibilidad */
@media (prefers-reduced-motion: reduce) {
    .fade-enter-active,
    .fade-leave-active,
    .transition-all {
        transition: none !important;
    }
}
</style>
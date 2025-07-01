<template>
  <li role="none">
    <!-- Main Item -->
    <div
      :class="[
        'relative flex items-center rounded-lg transition-all duration-200',
        'hover:bg-gray-700 focus-within:bg-gray-700',
        isActive ? 'bg-blue-600 hover:bg-blue-700' : '',
        isCollapsed ? 'justify-center px-2 py-3' : 'px-3 py-2'
      ]"
    >
      <!-- Collapsed State - Only Icon with Tooltip -->
      <div
        v-if="isCollapsed"
        class="relative group"
      >
        <component
          :is="hasChildren ? 'button' : linkComponent"
          :href="!hasChildren ? item.href : undefined"
          @click="handleClick"
          :class="[
            'flex items-center justify-center w-10 h-10 rounded-lg',
            'focus:outline-none focus:ring-2 focus:ring-blue-500',
            'transition-colors duration-200 text-base'
          ]"
          :aria-label="item.label"
          :role="hasChildren ? 'button' : 'menuitem'"
          :aria-expanded="hasChildren ? isExpanded : undefined"
        >
          <component
            :is="item.icon"
            class="w-5 h-5"
            aria-hidden="true"
          />
        </component>

        <!-- Tooltip -->
        <div
          class="absolute left-full ml-3 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50 whitespace-nowrap"
          role="tooltip"
        >
          {{ item.label }}
          <!-- Tooltip Arrow -->
          <div class="absolute top-1/2 left-0 transform -translate-y-1/2 -translate-x-1 w-2 h-2 bg-gray-900 rotate-45"></div>
        </div>
      </div>

      <!-- Expanded State - Icon + Text -->
      <template v-else>
        <component
          :is="hasChildren ? 'button' : linkComponent"
          :href="!hasChildren ? item.href : undefined"
          @click="handleClick"
          :class="[
            'flex items-center flex-1 text-left rounded-lg min-w-0',
            'focus:outline-none focus:ring-2 focus:ring-blue-500',
            'transition-colors duration-200 font-medium',
            'px-3 py-2 text-sm'
          ]"
          :role="hasChildren ? 'button' : 'menuitem'"
          :aria-expanded="hasChildren ? isExpanded : undefined"
        >
          <component
            :is="item.icon"
            class="w-5 h-5 mr-3 flex-shrink-0"
            aria-hidden="true"
          />
          
          <span class="flex-1 truncate">{{ item.label }}</span>
          
          <!-- Badge -->
          <span
            v-if="item.badge"
            :class="[
              'ml-2 px-2 py-1 text-xs font-medium rounded-full flex-shrink-0',
              item.badge.color || 'bg-gray-600'
            ]"
          >
            {{ item.badge.text }}
          </span>
          
          <!-- Chevron for expandable items -->
          <ChevronDownIcon
            v-if="hasChildren"
            :class="[
              'w-4 h-4 ml-2 transition-transform duration-200 flex-shrink-0',
              isExpanded ? 'rotate-180' : ''
            ]"
            aria-hidden="true"
          />
        </component>
      </template>
    </div>

    <!-- Submenu -->
    <Transition
      name="submenu"
      @enter="onEnter"
      @leave="onLeave"
    >
      <ul
        v-if="hasChildren && isExpanded && !isCollapsed"
        class="mt-1 ml-4 space-y-1 border-l border-gray-700 pl-4"
        role="menu"
        :aria-label="`Submenu de ${item.label}`"
      >
        <li
          v-for="child in item.children"
          :key="child.id"
          role="none"
        >
          <component
            :is="linkComponent"
            :href="child.href"
            @click="() => handleChildClick(child)"
            :class="[
              'flex items-center px-3 py-2 text-sm rounded-lg transition-colors duration-200 min-w-0',
              'hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500',
              activeItem === child.id ? 'bg-blue-600 text-white' : 'text-gray-300'
            ]"
            role="menuitem"
          >
            <span class="w-5 h-5 mr-3 flex-shrink-0 flex items-center justify-center">
              <div class="w-1.5 h-1.5 bg-gray-400 rounded-full"></div>
            </span>
            <span class="truncate">{{ child.label }}</span>
          </component>
        </li>
      </ul>
    </Transition>
  </li>
</template>

<script setup>
// Paso 1: Importaciones necesarias
import { ref, computed } from 'vue';
import { ChevronDownIcon } from '@heroicons/vue/24/outline';
import { usePage } from '@inertiajs/vue3'; // Importamos usePage para acceder a la ruta actual

// Paso 2: Definición de propiedades que recibe el componente
const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  isCollapsed: {
    type: Boolean,
    default: false
  },
  linkComponent: {
    type: [String, Object],
    default: 'a'
  }
});

// Paso 3: Eventos que emite el componente
const emit = defineEmits(['item-click']);

// Paso 4: Estado reactivo para controlar si el submenú está expandido
const isExpanded = ref(false);

// Paso 5: Propiedades computadas
// - Determina si el ítem tiene hijos
const hasChildren = computed(() => props.item.children && props.item.children.length > 0);

// - Obtiene la información de la ruta actual
const page = usePage();

// - Determina si el ítem está activo basado en la ruta actual
const isActive = computed(() => {
  // Para ítems con hijos: verifica si alguna ruta hija coincide con la ruta actual
  if (hasChildren.value) {
    return props.item.children.some(child => 
      page.url.startsWith(child.href)
    );
  }
  
  // Para ítems sin hijos: verifica si la ruta coincide
  return page.url.startsWith(props.item.href);
});

// Paso 6: Métodos del componente
// - Maneja el clic en el ítem principal
const handleClick = (event) => {
  if (hasChildren.value) {
    // Si tiene hijos, alterna la expansión y previene la navegación
    event.preventDefault();
    isExpanded.value = !isExpanded.value;
  } else {
    // Si no tiene hijos, emite el evento para navegar
    emit('item-click', props.item);
  }
};

// - Maneja el clic en un ítem hijo
const handleChildClick = (child) => {
  emit('item-click', child);
};

// Paso 7: Métodos de animación para el submenú
const onEnter = (el) => {
  // Prepara la animación de entrada
  el.style.height = '0';
  el.offsetHeight; // Fuerza el reflow para que la animación funcione
  el.style.height = el.scrollHeight + 'px';
};

const onLeave = (el) => {
  // Prepara la animación de salida
  el.style.height = el.scrollHeight + 'px';
  el.offsetHeight; // Fuerza el reflow
  el.style.height = '0';
};
</script>

<style scoped>
.submenu-enter-active,
.submenu-leave-active {
  transition: height 0.3s ease-in-out;
  overflow: hidden;
}

.submenu-enter-from,
.submenu-leave-to {
  height: 0;
}
</style>
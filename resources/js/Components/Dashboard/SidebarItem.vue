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
import { ref, computed } from 'vue'
import { ChevronDownIcon } from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  isCollapsed: {
    type: Boolean,
    default: false
  },
  activeItem: {
    type: String,
    default: ''
  },
  linkComponent: {
    type: [String, Object],
    default: 'a'
  }
})

// Emits
const emit = defineEmits(['item-click'])

// State
const isExpanded = ref(false)

// Computed
const hasChildren = computed(() => props.item.children && props.item.children.length > 0)
const isActive = computed(() => {
  if (props.activeItem === props.item.id) return true
  if (hasChildren.value) {
    return props.item.children.some(child => child.id === props.activeItem)
  }
  return false
})

// Methods
const handleClick = (event) => {
  if (hasChildren.value) {
    event.preventDefault()
    isExpanded.value = !isExpanded.value
  } else {
    emit('item-click', props.item)
  }
}

const handleChildClick = (child) => {
  emit('item-click', child)
}

// Animation methods
const onEnter = (el) => {
  el.style.height = '0'
  el.offsetHeight // trigger reflow
  el.style.height = el.scrollHeight + 'px'
}

const onLeave = (el) => {
  el.style.height = el.scrollHeight + 'px'
  el.offsetHeight // trigger reflow
  el.style.height = '0'
}
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
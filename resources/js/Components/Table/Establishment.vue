<template>
  <!-- Contenedor principal del componente -->
  <div>
    <!-- 1. SECCIÓN DE CONTROLES SUPERIORES -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
      <!-- 
        Campo de búsqueda por nombre:
        - v-model: Enlaza con la variable 'busqueda' para filtrado reactivo
        - Diseño responsive: Ocupa 1/3 del ancho en pantallas medianas/grandes
      -->
      <input
        v-model="busqueda"
        type="text"
        placeholder="Buscar por nombre..."
        class="border border-gray-300 rounded px-4 py-2 w-full md:w-1/3"
      />

      <!-- Contenedor para botón y filtro -->
      <div class="flex items-center space-x-4 w-full md:w-auto">
        <!-- 
          Botón "Agregar establecimiento":
          - Componente reutilizable ButtonAdd
          - @openModal: Emite evento al componente padre
          - Estilo personalizado con fondo verde
        -->
        <ButtonAdd 
          @openModal="$emit('openModal')"
          label="Agregar establecimiento"
          class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:border-green-800 focus:ring-green-300"
        />

        <!-- 
          Selector de región para filtrado:
          - v-model: Enlaza con 'regionSeleccionada'
          - Opciones generadas dinámicamente desde 'regionesUnicas'
        -->
        <select
          v-model="regionSeleccionada"
          class="border border-gray-300 rounded px-4 py-2 w-full md:w-48"
        >
          <option value="">Todas las regiones</option>
          <option v-for="region in regionesUnicas" :key="region" :value="region">
            {{ region }}
          </option>
        </select>
      </div>
    </div>

    <!-- 2. COMPONENTE TABLA PRINCIPAL -->
    <TablaBase
      titulo="Lista de Establecimientos"
      :columnas="columnas"
      :datos="establecimientosFiltrados"
      :cargando="false"
      texto-vacio="No hay establecimientos que coincidan"
      texto-cargando="Cargando..."
    >
      <!-- 
        SLOTS PERSONALIZADOS:
        Permiten personalizar cómo se muestran ciertas columnas
      -->

      <!-- Slot para columna Nombre: Muestra ID + Nombre -->
      <template #nombre="{ fila }">
        <div class="flex items-center">
          <span class="font-mono bg-gray-100 rounded px-2 py-1 mr-2">
            #{{ fila.id }}
          </span>
          <span>{{ fila.name }}</span>
        </div>
      </template>

      <!-- Slot para columna Estado: Muestra badge colorido -->
      <template #estado="{ fila }">
        <span :class="fila.is_active ? 'badge-success' : 'badge-danger'" class="badge">
          {{ fila.is_active ? 'Activo' : 'Inactivo' }}
        </span>
      </template>

      <!-- Slot para columna Acciones: Botones Editar/Inhabilitar -->
      <template #acciones="{ fila }">
        <div class="flex space-x-2" v-if="fila">
          <button @click="editarEstablecimiento(fila.id)" 
                  class="text-blue-600 hover:text-blue-800">
            Editar
          </button>
          <button @click="toggleEstado(fila.id, fila.is_active)" 
                  :class="fila.is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800'">
            {{ fila.is_active ? 'Inhabilitar' : 'Habilitar' }}
          </button>
        </div>
      </template>
    </TablaBase>
  </div>
</template>

<script setup>
/*
 * IMPORTS (Engranaje externo):
 * - Vue: Funcionalidades reactivas
 * - TablaBase: Componente reutilizable para mostrar datos tabulares
 * - ButtonAdd: Componente reutilizable de botón
 * - Inertia: Para navegación SPA y llamadas HTTP
 */
import { computed, ref } from 'vue'
import TablaBase from '@/Components/TablaBase/TablaBase.vue'
import ButtonAdd from '@/Components/Bottom/ButtonAdd.vue'
import { Inertia } from '@inertiajs/inertia'

/*
 * 1. DEFINICIÓN DE EVENTOS:
 * - openModal: Comunica al componente padre que debe abrir el modal
 */
const emit = defineEmits(['openModal'])

/*
 * 2. PROPS (Datos de entrada):
 * - establishments: Array de establecimientos a mostrar
 */
const props = defineProps({
  establishments: {
    type: Array,
    default: () => []
  }
})

/*
 * 3. REACTIVIDAD Y COMPUTADOS:
 */

// Lista de establecimientos (reactiva a cambios en props)
const establecimientos = computed(() => props.establishments || [])

// Definición de columnas para la tabla
const columnas = [
  { 
    titulo: 'Nombre', 
    slot: 'nombre',       // Usa slot personalizado
    sortable: true,       // Permite ordenamiento
    campo: 'name'         // Campo para ordenamiento
  },
  { 
    titulo: 'Estado', 
    slot: 'estado',
    sortable: true,
    campo: 'is_active'
  },
  { titulo: 'Dirección', campo: 'address' },
  { titulo: 'Cupo PIE', campo: 'pie_quota_max' },
  { titulo: 'Comuna', campo: 'commune.name' },
  { titulo: 'Región', campo: 'commune.region.name' },
  { 
    titulo: 'Acciones', 
    slot: 'acciones',
    sortable: false       // No permite ordenamiento
  }
]

// Variables reactivas para filtros
const busqueda = ref('')             // Texto de búsqueda
const regionSeleccionada = ref('')   // Región seleccionada

/*
 * 4. FILTRADO DE DATOS:
 * - establecimientosFiltrados: Combina filtros de búsqueda y región
 */
const establecimientosFiltrados = computed(() => {
  if (!Array.isArray(establecimientos.value)) return []
  
  return establecimientos.value.filter(est => {
    // Filtro por nombre (case insensitive)
    const coincideNombre = est.name?.toLowerCase().includes(busqueda.value.toLowerCase())
    // Filtro por región (si hay alguna seleccionada)
    const coincideRegion = regionSeleccionada.value
      ? est.commune?.region?.name === regionSeleccionada.value
      : true
    
    return coincideNombre && coincideRegion
  })
})

/*
 * 5. DATOS PARA FILTRO DE REGIONES:
 * - regionesUnicas: Lista de regiones sin duplicados, ordenadas
 */
const regionesUnicas = computed(() => {
  if (!Array.isArray(establecimientos.value)) return []
  
  const regiones = establecimientos.value
    .map(est => est.commune?.region?.name)  // Extrae nombres de regiones
    .filter(Boolean)                        // Filtra valores nulos/undefined
  
  return [...new Set(regiones)].sort()      // Elimina duplicados y ordena
})

/*
 * 6. MÉTODOS (Lógica de interacción):
 */

// Navega a la página de edición de un establecimiento
function editarEstablecimiento(id) {
  Inertia.visit(route('establishments.edit', id))
}

// Alterna el estado activo/inactivo de un establecimiento
function toggleEstado(id, estadoActual) {
  if (confirm(`¿Estás seguro de ${estadoActual ? 'inhabilitar' : 'habilitar'} este establecimiento?`)) {
    Inertia.put(route('establishments.update', id), {
      is_active: !estadoActual  // Invierte el estado actual
    })
  }
}
</script>
<template>
  <!-- Contenedor principal del componente -->
  <div>
    <!-- 1. SECCIÓN DE CONTROLES SUPERIORES -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
      <!-- Campo de búsqueda por nombre -->
      <input
        v-model="busqueda"
        type="text"
        placeholder="Buscar por nombre..."
        class="border border-gray-300 rounded px-4 py-2 w-full md:w-1/3"
      />

      <!-- Contenedor para botón y filtro -->
      <div class="flex items-center space-x-4 w-full md:w-auto">
        <!-- Botón para abrir modal de nuevo establecimiento -->
        <ButtonAdd 
          @openModal="$emit('openModal')"
          label="Agregar establecimiento"
          class="bg-green-600 hover:bg-green-700 active:bg-green-800 focus:border-green-800 focus:ring-green-300"
        />

        <!-- Selector de región -->
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
      <!-- Slot para columna Nombre -->
      <template #nombre="{ fila }">
        <div class="flex items-center">
          <span class="font-mono bg-gray-100 rounded px-2 py-1 mr-2">
            #{{ fila.id }}
          </span>
          <span>{{ fila.name }}</span>
        </div>
      </template>

      <!-- Slot para columna Estado -->
      <template #estado="{ fila }">
        <ToggleBase 
          v-model="fila.is_active"
          @update:modelValue="toggleEstado(fila.id, $event)"
          :active-label="fila.is_active ? 'Activo' : 'Inactivo'"
          class="ml-2"
        />
      </template>

      <!-- Slot para columna Acciones: Solo Editar -->
      <template #acciones="{ fila }">
        <div class="flex items-center" v-if="fila">
          <!-- Ícono para editar establecimiento -->
          <button @click="editarEstablecimiento(fila.id)" class="text-blue-600 hover:text-blue-800">
            <PencilSquareIcon class="w-5 h-5" />
          </button>
        </div>
      </template>
    </TablaBase>
  </div>
</template>

<script setup>
/*
 * IMPORTACIONES:
 * - Vue: funcionalidades reactivas
 * - TablaBase: tabla reutilizable
 * - ButtonAdd: botón personalizado
 * - ToggleBase: interruptor de estado
 * - Heroicons: ícono de lápiz
 */
import { computed, ref } from 'vue'
import TablaBase from '@/Components/TablaBase/TablaBase.vue'
import ButtonAdd from '@/Components/Bottom/ButtonAdd.vue'
import ToggleBase from '@/Components/Toggle/ToggleBase.vue'
import { Inertia } from '@inertiajs/inertia'
import { PencilSquareIcon } from '@heroicons/vue/24/outline' // Ícono para editar

const emit = defineEmits(['openModal'])

const props = defineProps({
  establishments: {
    type: Array,
    default: () => []
  }
})

// Computadas
const establecimientos = computed(() => props.establishments || [])

const columnas = [
  { titulo: 'Nombre', slot: 'nombre', sortable: true, campo: 'name' },
  { titulo: 'Estado', slot: 'estado', sortable: true, campo: 'is_active' },
  { titulo: 'Dirección', campo: 'address' },
  { titulo: 'Cupo PIE', campo: 'pie_quota_max' },
  { titulo: 'Comuna', campo: 'commune.name' },
  { titulo: 'Región', campo: 'commune.region.name' },
  { titulo: 'Acciones', slot: 'acciones', sortable: false }
]

// Filtros
const busqueda = ref('')
const regionSeleccionada = ref('')
//"Filtrar dinámicamente la lista de establecimientos según el nombre ingresado
//  en el buscador y la región seleccionada por el usuario."
const establecimientosFiltrados = computed(() => {
  // 1. Verificamos si 'establecimientos.value' es un arreglo válido.
  //    Esto es una medida de seguridad para evitar errores si aún no hay datos.
  if (!Array.isArray(establecimientos.value)) return []

  // 2. Utilizamos el método .filter() para recorrer todos los establecimientos.
  //    Este método devuelve un nuevo arreglo con los elementos que cumplan ciertas condiciones.
  return establecimientos.value.filter(est => {
    
    // 3. Evaluamos si el nombre del establecimiento coincide con el texto de búsqueda:
    //    a) Convertimos 'est.name' y 'busqueda.value' a minúsculas para hacer la comparación insensible a mayúsculas.
    //    b) Usamos el método .includes() para ver si el texto buscado está contenido en el nombre.
    //    c) Si 'est.name' no existe (es undefined o null), usamos el operador ?. para evitar errores.
    const coincideNombre = est.name?.toLowerCase().includes(busqueda.value.toLowerCase())

    // 4. Evaluamos si la región del establecimiento coincide con la región seleccionada:
    //    a) Si 'regionSeleccionada' tiene un valor (es decir, se eligió una región),
    //       comparamos si 'est.commune.region.name' es igual a ese valor.
    //    b) Si NO hay región seleccionada (el valor es una cadena vacía), consideramos que cualquier región es válida (true).
    const coincideRegion = regionSeleccionada.value
      ? est.commune?.region?.name === regionSeleccionada.value
      : true

    // 5. Solo incluimos en el resultado los establecimientos que:
    //    - Cumplen con el filtro de nombre (coincideNombre === true)
    //    - Y también cumplen con el filtro de región (coincideRegion === true)
    return coincideNombre && coincideRegion
  })
})


const regionesUnicas = computed(() => {
  if (!Array.isArray(establecimientos.value)) return []
  const regiones = establecimientos.value
    .map(est => est.commune?.region?.name)
    .filter(Boolean)
  return [...new Set(regiones)].sort()
})

// Navega a la página de edición
function editarEstablecimiento(id) {
  Inertia.visit(route('establishments.edit', id))
}

// FUNCIÓN TOGGLE CORREGIDA (solo una versión)
function toggleEstado(id, nuevoEstado) {
  console.log('=== INICIO TOGGLE DEBUG ===');
  console.log('ID:', id);
  console.log('Nuevo estado:', nuevoEstado);
  
  // Construir URL manualmente para evitar problemas con route()
  const url = `/establishments/${id}`;
  console.log('URL construida manualmente:', url);
  
  Inertia.put(url, {
    is_active: nuevoEstado
  }, {
    preserveScroll: true,
    onStart: () => {
      console.log('🚀 Iniciando petición PUT a:', url);
    },
    onSuccess: (page) => {
      console.log('✅ Éxito:', page);
      const index = establecimientos.value.findIndex(e => e.id === id);
      if (index !== -1) {
        establecimientos.value[index].is_active = nuevoEstado;
      }
    },
    onError: (errors) => {
      console.error('❌ Error en petición:', errors);
      // Revertir visualmente si falla
      const index = establecimientos.value.findIndex(e => e.id === id);
      if (index !== -1) {
        establecimientos.value[index].is_active = !nuevoEstado;
      }
    },
    onFinish: () => {
      console.log('🏁 Petición terminada');
      console.log('=== FIN TOGGLE DEBUG ===');
    }
  });
}
</script>
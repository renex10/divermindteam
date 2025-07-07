<template>
  <TablaBase
    :datos="specialties.data"
    :columnas="columnas"
    :titulo="'Especialidades'"
    :cargando="false"
    :paginacion="true"
    :items-por-pagina="specialties.per_page"
    :pagina-actual="specialties.current_page"
    :total-items="specialties.total"
    :seleccionable="true"
    @cambiar-pagina="cambiarPagina"
    @fila-seleccionada="seleccionarEspecialidad"
    @ordenar="ordenarDatos"
  >
<template #acciones>
  <Link
    :href="route('specialties.create')"
    class="btn btn-primary flex items-center gap-2"
  >
    <i class="icon-plus"></i>
    Nueva Especialidad
  </Link>
</template>


    <!-- Slot personalizado para la columna de descripción -->
    <template #descripcion="{ valor }">
      <div class="descripcion-cell">
        <p class="descripcion-texto" :title="valor">
          {{ valor ? (valor.length > 100 ? valor.substring(0, 100) + '...' : valor) : '-' }}
        </p>
      </div>
    </template>

    <!-- Slot para acciones de fila -->
    <template #acciones-fila="{ fila }">
      <div class="acciones-fila">
        <button 
          @click="editarEspecialidad(fila)" 
          class="btn-accion btn-editar"
          title="Editar"
        >
          <i class="icon-edit"></i>
        </button>
        <button 
          @click="eliminarEspecialidad(fila)" 
          class="btn-accion btn-eliminar"
          title="Eliminar"
        >
          <i class="icon-trash"></i>
        </button>
      </div>
    </template>

    <!-- Slot para fechas formateadas -->
    <template #created_at="{ valor }">
      <span class="fecha-formato">
        {{ formatearFechaEspecial(valor) }}
      </span>
    </template>
  </TablaBase>
</template>

<script>
import TablaBase from '@/Components/TablaBase/TablaBase.vue'
import { router } from '@inertiajs/vue3'

export default {
  name: 'SpecialtiesTable',
  components: {
    TablaBase
  },
  props: {
    specialties: {
      type: Object,
      required: true,
      validator: (value) => {
        return value && Array.isArray(value.data)
      }
    }
  },
  data() {
    return {
      columnas: [
        {
          titulo: 'ID',
          campo: 'id',
          ancho: '80px',
          sortable: true,
          claseTexto: 'text-center font-bold'
        },
        {
          titulo: 'Nombre',
          campo: 'name',
          sortable: true,
          claseTexto: 'font-medium'
        },
        {
          titulo: 'Descripción',
          campo: 'description',
          slot: 'descripcion',
          ancho: '40%'
        },
        {
          titulo: 'Fecha Creación',
          campo: 'created_at',
          tipo: 'fecha',
          sortable: true,
          slot: 'created_at',
          ancho: '150px'
        },
        {
          titulo: 'Última Actualización',
          campo: 'updated_at',
          tipo: 'fecha',
          sortable: true,
          ancho: '150px'
        },
        {
          titulo: 'Acciones',
          campo: '',
          slot: 'acciones-fila',
          ancho: '120px',
          claseTexto: 'text-center'
        }
      ]
    }
  },
  methods: {
    cambiarPagina(pagina) {
      router.get(route('specialties.index'), { 
        page: pagina 
      }, {
        preserveState: true,
        preserveScroll: true
      })
    },
    
    seleccionarEspecialidad(especialidad, index) {
      console.log('Especialidad seleccionada:', especialidad, 'Índice:', index)
      // Aquí puedes manejar la selección de la fila
    },
    
    ordenarDatos(orden) {
      router.get(route('specialties.index'), {
        sort: orden.campo,
        direction: orden.direccion
      }, {
        preserveState: true,
        preserveScroll: true
      })
    },
    
    editarEspecialidad(especialidad) {
      router.get(route('specialties.edit', especialidad.id))
    },
    
    eliminarEspecialidad(especialidad) {
      if (confirm(`¿Estás seguro de que quieres eliminar la especialidad "${especialidad.name}"?`)) {
        router.delete(route('specialties.destroy', especialidad.id), {
          onSuccess: () => {
            console.log('Especialidad eliminada exitosamente')
          },
          onError: (errors) => {
            console.error('Error al eliminar:', errors)
          }
        })
      }
    },
    
    formatearFechaEspecial(fecha) {
      if (!fecha) return '-'
      
      try {
        const date = new Date(fecha)
        return date.toLocaleDateString('es-CL', {
          year: 'numeric',
          month: 'short',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        })
      } catch (error) {
        console.error('Error al formatear fecha:', error)
        return fecha
      }
    }
  }
}
</script>

<style scoped>
.btn {
  padding: 0.5rem 1rem;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-primary {
  background: #16425b;
  color: white;
}

.btn-primary:hover {
  background: #0f3547;
}

.descripcion-cell {
  max-width: 300px;
}

.descripcion-texto {
  margin: 0;
  line-height: 1.4;
  color: #4b5563;
}

.acciones-fila {
  display: flex;
  gap: 0.5rem;
  justify-content: center;
}

.btn-accion {
  padding: 0.375rem;
  border-radius: 4px;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
}

.btn-editar {
  background: #f59e0b;
  color: white;
}

.btn-editar:hover {
  background: #d97706;
}

.btn-eliminar {
  background: #ef4444;
  color: white;
}

.btn-eliminar:hover {
  background: #dc2626;
}

.fecha-formato {
  font-size: 0.875rem;
  color: #6b7280;
}

/* Iconos simples con CSS */
.icon-plus::before { content: '+'; }
.icon-edit::before { content: '✏️'; }
.icon-trash::before { content: '🗑️'; }
</style>
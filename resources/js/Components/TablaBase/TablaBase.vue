<template>
<!--   resources\js\Components\TablaBase\TablaBase.vue -->
  <div class="tabla-container">
    <!-- Título y acciones opcionales -->
    <div v-if="titulo || $slots.acciones" class="tabla-header">
      <h3 v-if="titulo" class="tabla-titulo">{{ titulo }}</h3>
      <div v-if="$slots.acciones" class="tabla-acciones">
        <slot name="acciones"></slot>
      </div>
    </div>

    <!-- Tabla responsive -->
    <div class="tabla-wrapper">
      <table class="tabla-base">
        <!-- Encabezado -->
        <thead class="tabla-head">
          <tr>
            <th 
              v-for="(columna, index) in columnas" 
              :key="index"
              :class="[
                'tabla-th',
                columna.clase || '',
                { 'sortable': columna.sortable }
              ]"
              :style="{ width: columna.ancho || 'auto' }"
              @click="columna.sortable ? ordenar(columna.campo) : null"
            >
              <div class="th-content">
                <span>{{ columna.titulo }}</span>
                <div v-if="columna.sortable" class="sort-icons">
                  <i 
                    :class="[
                      'sort-icon',
                      getSortClass(columna.campo)
                    ]"
                  ></i>
                </div>
              </div>
            </th>
          </tr>
        </thead>

        <!-- Cuerpo de la tabla -->
        <tbody class="tabla-body">
          <tr 
            v-for="(fila, indexFila) in datosOrdenados" 
            :key="indexFila"
            class="tabla-tr"
            :class="{ 'fila-seleccionada': filaSeleccionada === indexFila }"
            @click="seleccionarFila(fila, indexFila)"
          >
            <td 
              v-for="(columna, indexCol) in columnas" 
              :key="indexCol"
              class="tabla-td"
              :class="columna.claseTexto || ''"
              :data-label="columna.titulo"
            >
              <!-- Slot personalizado para la columna -->
              <slot 
                v-if="columna.slot" 
                :name="columna.slot" 
                :fila="fila" 
                :valor="obtenerValor(fila, columna.campo)"
                :index="indexFila"
              ></slot>
              
              <!-- Contenido por defecto -->
              <span v-else-if="columna.tipo === 'badge'" 
                    :class="['badge', getBadgeClass(obtenerValor(fila, columna.campo))]">
                {{ formatearValor(obtenerValor(fila, columna.campo), columna) }}
              </span>
              
              <span v-else-if="columna.tipo === 'moneda'" class="moneda">
                {{ formatearMoneda(obtenerValor(fila, columna.campo)) }}
              </span>
              
              <span v-else-if="columna.tipo === 'fecha'" class="fecha">
                {{ formatearFecha(obtenerValor(fila, columna.campo)) }}
              </span>
              
              <span v-else>
                {{ formatearValor(obtenerValor(fila, columna.campo), columna) }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Estado vacío -->
      <div v-if="!datos || datos.length === 0" class="tabla-vacia">
        <div class="vacia-content">
          <i class="vacia-icon">📋</i>
          <p class="vacia-texto">{{ textoVacio || 'No hay datos disponibles' }}</p>
        </div>
      </div>

      <!-- Loading state -->
      <div v-if="cargando" class="tabla-loading">
        <div class="loading-spinner"></div>
        <p>{{ textoCargando || 'Cargando datos...' }}</p>
      </div>
    </div>

    <!-- Paginación opcional -->
    <div v-if="paginacion && !cargando" class="tabla-paginacion">
      <div class="paginacion-info">
        Mostrando {{ (paginaActual - 1) * itemsPorPagina + 1 }} - 
        {{ Math.min(paginaActual * itemsPorPagina, totalItems) }} 
        de {{ totalItems }} registros
      </div>
      <div class="paginacion-controles">
        <button 
          @click="cambiarPagina(paginaActual - 1)"
          :disabled="paginaActual <= 1"
          class="btn-paginacion"
        >
          ‹ Anterior
        </button>
        
        <span class="pagina-actual">
          Página {{ paginaActual }} de {{ totalPaginas }}
        </span>
        
        <button 
          @click="cambiarPagina(paginaActual + 1)"
          :disabled="paginaActual >= totalPaginas"
          class="btn-paginacion"
        >
          Siguiente ›
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TablaBase',
  props: {
    // Datos principales
    datos: {
      type: Array,
      default: () => [],
      validator: value => Array.isArray(value) || value === null
    },
    columnas: {
      type: Array,
      required: true,
      validator: value => Array.isArray(value) && value.every(col => typeof col.titulo === 'string')
    },
    
    // Configuración visual
    titulo: {
      type: String,
      default: ''
    },
    textoVacio: {
      type: String,
      default: 'No hay datos disponibles'
    },
    textoCargando: {
      type: String,
      default: 'Cargando datos...'
    },
    
    // Estados
    cargando: {
      type: Boolean,
      default: false
    },
    
    // Paginación
    paginacion: {
      type: Boolean,
      default: false
    },
    itemsPorPagina: {
      type: Number,
      default: 10,
      validator: value => value > 0
    },
    paginaActual: {
      type: Number,
      default: 1,
      validator: value => value >= 1
    },
    totalItems: {
      type: Number,
      default: 0,
      validator: value => value >= 0
    },
    
    // Configuración de ordenamiento
    ordenDefecto: {
      type: Object,
      default: () => ({ campo: null, direccion: 'asc' }),
      validator: value => (
        value !== null &&
        typeof value === 'object' &&
        (value.campo === null || typeof value.campo === 'string') &&
        ['asc', 'desc'].includes(value.direccion)
      )
    },
    
    // Selección de filas
    seleccionable: {
      type: Boolean,
      default: false
    }
  },
  
  data() {
    return {
      ordenActual: { ...this.ordenDefecto },
      filaSeleccionada: null
    }
  },
  
  computed: {
    datosOrdenados() {
      if (!this.datos || this.datos.length === 0) return []
      
      if (!this.ordenActual.campo) return this.datos
      
      return [...this.datos].sort((a, b) => {
        const valorA = this.obtenerValor(a, this.ordenActual.campo)
        const valorB = this.obtenerValor(b, this.ordenActual.campo)
        
        // Manejo de valores nulos/undefined en la ordenación
        if (valorA === null || valorA === undefined) return 1
        if (valorB === null || valorB === undefined) return -1
        
        let resultado = 0
        if (valorA < valorB) resultado = -1
        else if (valorA > valorB) resultado = 1
        
        return this.ordenActual.direccion === 'desc' ? -resultado : resultado
      })
    },
    
    totalPaginas() {
      return Math.max(1, Math.ceil(this.totalItems / this.itemsPorPagina))
    }
  },
  
  methods: {
    obtenerValor(objeto, campo) {
      // 1. Validaciones iniciales
      if (objeto === null || objeto === undefined) {
        console.warn('TablaBase: Objeto no válido', objeto)
        return null
      }
      
      if (typeof campo !== 'string' || campo.trim() === '') {
        console.warn('TablaBase: Campo no válido', campo)
        return null
      }

      // 2. Búsqueda segura con reducción
      try {
        return campo.split('.').reduce((obj, key) => {
          if (obj === null || obj === undefined) return undefined
          // Manejo especial para arrays
          if (Array.isArray(obj) && !isNaN(key)) {
            return obj[parseInt(key)]
          }
          return obj[key]
        }, objeto)
      } catch (error) {
        console.error('TablaBase: Error al acceder a propiedad', {
          objeto,
          campo,
          error
        })
        return null
      }
    },
    
    formatearValor(valor, columna) {
      if (valor === null || valor === undefined) return '-'
      
      // Permite formateadores personalizados por columna
      if (columna.formatear && typeof columna.formatear === 'function') {
        try {
          return columna.formatear(valor)
        } catch (error) {
          console.error('TablaBase: Error en formateador personalizado', {
            valor,
            columna,
            error
          })
          return valor
        }
      }
      
      // Formateo automático según tipo de columna
      if (columna.tipo === 'moneda') return this.formatearMoneda(valor)
      if (columna.tipo === 'fecha') return this.formatearFecha(valor)
      if (columna.tipo === 'badge') return this.getBadgeText(valor)
      
      return valor
    },
    
    formatearMoneda(valor) {
      if (valor === null || valor === undefined) return '-'
      try {
        return new Intl.NumberFormat('es-CL', {
          style: 'currency',
          currency: 'CLP'
        }).format(valor)
      } catch {
        return valor
      }
    },
    
    formatearFecha(valor) {
      if (!valor) return '-'
      try {
        return new Date(valor).toLocaleDateString('es-CL')
      } catch {
        return valor
      }
    },
    
    getBadgeClass(valor) {
      if (valor === null || valor === undefined) return 'badge-default'
      
      const valorStr = String(valor).toLowerCase()
      const clases = {
        'activo': 'badge-success',
        'inactivo': 'badge-danger',
        'pendiente': 'badge-warning',
        'completado': 'badge-success',
        'cancelado': 'badge-danger',
        'true': 'badge-success',
        'false': 'badge-danger'
      }
      return clases[valorStr] || 'badge-default'
    },
    
    getBadgeText(valor) {
      if (valor === null || valor === undefined) return 'N/A'
      const valorStr = String(valor).toLowerCase()
      const textos = {
        'activo': 'Activo',
        'inactivo': 'Inactivo',
        'pendiente': 'Pendiente',
        'completado': 'Completado',
        'cancelado': 'Cancelado',
        'true': 'Sí',
        'false': 'No'
      }
      return textos[valorStr] || valor
    },
    
    ordenar(campo) {
      if (!this.columnas.some(col => col.campo === campo && col.sortable)) {
        console.warn(`Intento de ordenar por campo no sortable: ${campo}`)
        return
      }
      
      if (this.ordenActual.campo === campo) {
        this.ordenActual.direccion = this.ordenActual.direccion === 'asc' ? 'desc' : 'asc'
      } else {
        this.ordenActual = { campo, direccion: 'asc' }
      }
      
      this.$emit('ordenar', this.ordenActual)
    },
    
    getSortClass(campo) {
      if (this.ordenActual.campo !== campo) return 'sort-none'
      return this.ordenActual.direccion === 'asc' ? 'sort-asc' : 'sort-desc'
    },
    
    seleccionarFila(fila, index) {
      if (!this.seleccionable) return
      
      // Validar que el índice esté dentro de los límites
      if (index < 0 || index >= this.datosOrdenados.length) {
        console.warn('Índice de fila fuera de rango', index)
        return
      }
      
      this.filaSeleccionada = this.filaSeleccionada === index ? null : index
      this.$emit('fila-seleccionada', fila, index)
    },
    
    cambiarPagina(nuevaPagina) {
      const pagina = parseInt(nuevaPagina)
      if (isNaN(pagina)) {
        console.warn('Número de página no válido', nuevaPagina)
        return
      }
      
      if (pagina >= 1 && pagina <= this.totalPaginas) {
        this.$emit('cambiar-pagina', pagina)
      } else {
        console.warn('Intento de navegar a página fuera de rango', pagina)
      }
    }
  }
}
</script>

<style scoped>
.tabla-container {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  overflow: hidden;
  margin-bottom: 1.5rem;
}

.tabla-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.tabla-titulo {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.tabla-acciones {
  display: flex;
  gap: 0.75rem;
}

.tabla-wrapper {
  position: relative;
  overflow-x: auto;
}

.tabla-base {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.875rem;
}

.tabla-head {
  background: #16425b;
  position: sticky;
  top: 0;
  z-index: 10;
}

.tabla-th {
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  color: white;
  border-bottom: 2px solid rgba(255, 255, 255, 0.1);
  white-space: nowrap;
  position: relative;
}

.tabla-th.sortable {
  cursor: pointer;
  user-select: none;
  transition: background-color 0.2s;
}

.tabla-th.sortable:hover {
  background: rgba(255, 255, 255, 0.1);
}

.th-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.5rem;
}

.sort-icons {
  display: flex;
  flex-direction: column;
  font-size: 0.75rem;
  opacity: 0.7;
}

.sort-icon {
  transition: opacity 0.2s;
}

.sort-icon.sort-asc::before {
  content: '▲';
  opacity: 1;
}

.sort-icon.sort-desc::before {
  content: '▼';
  opacity: 1;
}

.sort-icon.sort-none::before {
  content: '↕';
}

.tabla-body {
  background: white;
}

.tabla-tr {
  transition: background-color 0.2s;
  cursor: pointer;
}

.tabla-tr:hover {
  background: #f1f5f9;
}

.tabla-tr.fila-seleccionada {
  background: #dbeafe;
}

.tabla-td {
  padding: 1rem;
  border-bottom: 1px solid #e2e8f0;
  vertical-align: middle;
}

.tabla-td:first-child {
  font-weight: 500;
}

/* Badges */
.badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.badge-success {
  background: #dcfce7;
  color: #166534;
}

.badge-danger {
  background: #fecaca;
  color: #991b1b;
}

.badge-warning {
  background: #fef3c7;
  color: #92400e;
}

.badge-default {
  background: #f1f5f9;
  color: #475569;
}

/* Formateo especial */
.moneda {
  font-weight: 600;
  color: #059669;
}

.fecha {
  color: #6b7280;
}

/* Estado vacío */
.tabla-vacia {
  padding: 3rem;
  text-align: center;
  background: white;
}

.vacia-content {
  max-width: 300px;
  margin: 0 auto;
}

.vacia-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  display: block;
}

.vacia-texto {
  color: #6b7280;
  font-size: 1rem;
  margin: 0;
}

/* Loading */
.tabla-loading {
  padding: 3rem;
  text-align: center;
  background: white;
}

.loading-spinner {
  width: 2rem;
  height: 2rem;
  border: 3px solid #e2e8f0;
  border-top: 3px solid #16425b;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Paginación */
.tabla-paginacion {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
}

.paginacion-info {
  color: #6b7280;
  font-size: 0.875rem;
}

.paginacion-controles {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.btn-paginacion {
  padding: 0.5rem 1rem;
  border: 1px solid #d1d5db;
  background: white;
  color: #374151;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 0.875rem;
}

.btn-paginacion:hover:not(:disabled) {
  background: #f9fafb;
  border-color: #16425b;
}

.btn-paginacion:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagina-actual {
  font-weight: 500;
  color: #374151;
  font-size: 0.875rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .tabla-header {
    flex-direction: column;
    gap: 1rem;
    align-items: stretch;
  }
  
  .tabla-acciones {
    justify-content: center;
  }
  
  .tabla-base {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }
  
  .tabla-head,
  .tabla-body,
  .tabla-tr {
    display: block;
  }
  
  .tabla-tr {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    padding: 1rem;
    background: white;
  }
  
  .tabla-th {
    display: none;
  }
  
  .tabla-td {
    display: block;
    padding: 0.5rem 0;
    border: none;
    text-align: right;
    position: relative;
    padding-left: 50%;
  }
  
  .tabla-td::before {
    content: attr(data-label) ": ";
    position: absolute;
    left: 0;
    width: 45%;
    padding-right: 10px;
    white-space: nowrap;
    font-weight: 600;
    color: #374151;
    text-align: left;
  }
  
  .paginacion-controles {
    flex-direction: column;
    gap: 0.5rem;
  }
}

@media (max-width: 480px) {
  .tabla-container {
    margin: 0 -1rem;
    border-radius: 0;
  }
  
  .tabla-header,
  .tabla-paginacion {
    padding: 1rem;
  }
  
  .tabla-paginacion {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
}

/* Mejoras adicionales */
.tabla-th:first-child {
  border-top-left-radius: 0;
}

.tabla-th:last-child {
  border-top-right-radius: 0;
}

.tabla-tr:last-child .tabla-td {
  border-bottom: none;
}

/* Clases utilitarias */
.text-center { text-align: center; }
.text-right { text-align: right; }
.text-left { text-align: left; }
.font-bold { font-weight: 600; }
.text-sm { font-size: 0.875rem; }
.text-xs { font-size: 0.75rem; }
</style>
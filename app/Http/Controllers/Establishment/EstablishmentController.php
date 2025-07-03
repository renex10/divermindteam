<?php
// app/Http/Controllers/Establishment/EstablishmentController.php

namespace App\Http\Controllers\Establishment;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Models\Region;
use App\Models\Commune;
use App\Http\Resources\EstablishmentResource;
use App\Http\Resources\RegionResource;
use App\Http\Resources\CommuneResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Exception;

class EstablishmentController extends Controller
{
    /**
     * 1. Mostrar listado paginado de establecimientos junto con
     *    las regiones y comunas disponibles para los filtros y el modal.
     *
     *    - Obtiene los establecimientos con relaciones necesarias cargadas.
     *    - Obtiene regiones y comunas ordenadas alfabéticamente.
     *    - Envía los datos al frontend a través de Inertia con paginación y recursos para formatear datos.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // 1. Cargar establecimientos con paginación y relaciones necesarias para mostrar ubicación
        $establishments = Establishment::with('commune.region')->paginate(8);

        // 2. Obtener listado completo de regiones y comunas, ordenadas para desplegarlas en filtros y formulario
        $regions = Region::orderBy('name')->get();
        $communes = Commune::orderBy('name')->get();

        // 3. Enviar datos al frontend usando Inertia
        return Inertia::render('Dashboard/establishments/Index', [
            'establishments' => [
                // 3.1. Transformar colección con Resource para filtrar y formatear datos
                'data' => EstablishmentResource::collection($establishments)->resolve(),

                // 3.2. Proporcionar info necesaria para paginación en UI
                'pagination' => [
                    'total' => $establishments->total(),
                    'per_page' => $establishments->perPage(),
                    'current_page' => $establishments->currentPage(),
                    'last_page' => $establishments->lastPage(),
                    'prev_page_url' => $establishments->previousPageUrl(),
                    'next_page_url' => $establishments->nextPageUrl(),
                ],
            ],

            // 4. También enviar regiones y comunas usando sus Resources para controlar salida
            'regiones' => RegionResource::collection($regions),
            'comunas' => CommuneResource::collection($communes),
        ]);
    }

    /**
     * 2. Guardar un nuevo establecimiento en la base de datos.
     *
     *    - Valida la información recibida desde el formulario.
     *    - Crea un nuevo registro de establecimiento si la validación pasa.
     *    - Registra logs de éxito o error para auditoría y depuración.
     *    - Redirige al listado con mensaje de éxito o muestra errores en caso de fallo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            // Validar los datos entrantes con reglas estrictas
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'region_id' => ['required', 'integer', Rule::exists('regions', 'id')],
                'commune_id' => ['required', 'integer', Rule::exists('communes', 'id')],
                'pie_quota_max' => 'required|integer|min:0',
                'is_active' => 'required|boolean',
            ]);

            // Crear el establecimiento con los datos validados
            $establishment = Establishment::create($validated);

            // Registrar log indicando creación exitosa
            Log::info('Establecimiento creado con éxito', [
                'id' => $establishment->id,
                'datos' => $validated
            ]);

            // Redirigir al índice con mensaje de éxito
            return redirect()->route('establishments.index')
                             ->with('success', 'Establecimiento creado correctamente');

        } catch (ValidationException $ve) {
            // Captura y registra errores de validación para seguimiento
            Log::error('Error de validación al crear establecimiento', [
                'errors' => $ve->errors(),
                'input' => $request->all()
            ]);
            // Re-lanzar para que Inertia pueda mostrar errores en frontend
            throw $ve;

        } catch (Exception $e) {
            // Registrar errores inesperados para diagnóstico
            Log::error('Error inesperado al crear establecimiento', [
                'mensaje' => $e->getMessage(),
                'traza' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            // Retornar con mensaje de error genérico para el usuario
            return back()->withErrors('Ocurrió un error al guardar el establecimiento. Intenta nuevamente.');
        }
    }

    /**
     * 3. Actualizar el estado activo/inactivo de un establecimiento.
     *
     *    - Valida que el campo `is_active` sea booleano.
     *    - Actualiza solo este campo del establecimiento.
     *    - Registra logs detallados antes y después de la actualización para auditoría.
     *    - Retorna una respuesta con mensaje de éxito y datos actualizados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Establishment  $establishment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Establishment $establishment)
    {
        // Loguear información del request para depuración
        Log::info('=== UPDATE METHOD CALLED ===', [
            'establishment_id' => $establishment->id,
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl(),
            'request_data' => $request->all(),
            'headers' => $request->headers->all(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer')
        ]);

        // Validar que se reciba un booleano para el campo is_active
        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        // Actualizar el campo is_active en la base de datos
        $establishment->update(['is_active' => $validated['is_active']]);

        // Loguear resultado después de la actualización
        Log::info('=== UPDATE COMPLETED ===', [
            'establishment_id' => $establishment->id,
            'new_state' => $establishment->is_active
        ]);

        // Retornar a la vista previa con mensaje de éxito y datos actualizados
        return back()->with([
            'success' => 'Estado actualizado correctamente',
            'updatedData' => [
                'id' => $establishment->id,
                'is_active' => $establishment->is_active
            ]
        ]);
    }
}

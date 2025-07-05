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
     * @return \Inertia\Response
     */
    public function index()
    {
        $establishments = Establishment::with('commune.region')->paginate(8);
        $regions = Region::orderBy('name')->get();
        $communes = Commune::orderBy('name')->get();

        return Inertia::render('Dashboard/establishments/Index', [
            'establishments' => [
                'data' => EstablishmentResource::collection($establishments)->resolve(),
                'pagination' => [
                    'total' => $establishments->total(),
                    'per_page' => $establishments->perPage(),
                    'current_page' => $establishments->currentPage(),
                    'last_page' => $establishments->lastPage(),
                    'prev_page_url' => $establishments->previousPageUrl(),
                    'next_page_url' => $establishments->nextPageUrl(),
                ],
            ],
            'regiones' => RegionResource::collection($regions),
            'comunas' => CommuneResource::collection($communes),
        ]);
    }

    /**
     * 2. Guardar un nuevo establecimiento en la base de datos.
     * 
     * 🆕 MODIFICACIÓN: Retornar el establecimiento creado con sus relaciones
     * para facilitar la actualización optimista en el frontend.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
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
            
            // 🆕 MODIFICACIÓN CRÍTICA: Cargar relaciones para enviar al frontend
            $establishment->load(['commune.region']);

            Log::info('Establecimiento creado con éxito', [
                'id' => $establishment->id,
                'datos' => $validated,
                'establishment_with_relations' => $establishment->toArray()
            ]);

            // 🆕 MODIFICACIÓN: Retornar datos estructurados para actualización optimista
            return redirect()->route('establishments.index')
                ->with([
                    'success' => 'Establecimiento creado correctamente',
                    'newEstablishment' => [
                        'id' => $establishment->id,
                        'name' => $establishment->name,
                        'address' => $establishment->address,
                        'region_id' => $establishment->region_id,
                        'commune_id' => $establishment->commune_id,
                        'pie_quota_max' => $establishment->pie_quota_max,
                        'is_active' => $establishment->is_active,
                        'commune' => [
                            'id' => $establishment->commune->id,
                            'name' => $establishment->commune->name,
                            'region_id' => $establishment->commune->region_id,
                            'region' => [
                                'id' => $establishment->commune->region->id,
                                'name' => $establishment->commune->region->name
                            ]
                        ],
                        'created_at' => $establishment->created_at->toISOString(),
                        'updated_at' => $establishment->updated_at->toISOString()
                    ]
                ]);

        } catch (ValidationException $ve) {
            Log::error('Error de validación al crear establecimiento', [
                'errors' => $ve->errors(),
                'input' => $request->all()
            ]);
            throw $ve;

        } catch (Exception $e) {
            Log::error('Error inesperado al crear establecimiento', [
                'mensaje' => $e->getMessage(),
                'traza' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return back()->withErrors('Ocurrió un error al guardar el establecimiento. Intenta nuevamente.');
        }
    }

    /**
     * 3. Actualizar el estado activo/inactivo de un establecimiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Establishment  $establishment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Establishment $establishment)
    {
        Log::info('=== UPDATE METHOD CALLED ===', [
            'establishment_id' => $establishment->id,
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl(),
            'request_data' => $request->all(),
            'headers' => $request->headers->all(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer')
        ]);

        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $establishment->update(['is_active' => $validated['is_active']]);

        Log::info('=== UPDATE COMPLETED ===', [
            'establishment_id' => $establishment->id,
            'new_state' => $establishment->is_active
        ]);

        return back()->with([
            'success' => 'Estado actualizado correctamente',
            'updatedData' => [
                'id' => $establishment->id,
                'is_active' => $establishment->is_active
            ]
        ]);
    }
}
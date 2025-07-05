<?php

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

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'rbd' => ['required', 'integer', 'unique:establishments,rbd'],
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'region_id' => ['required', 'integer', Rule::exists('regions', 'id')], // Solo para validación
                'commune_id' => ['required', 'integer', Rule::exists('communes', 'id')],
                'pie_quota_max' => 'required|integer|min:0',
                'is_active' => 'required|boolean',
            ]);

            // Verificar que la comuna pertenece a la región seleccionada
            $commune = Commune::find($validated['commune_id']);
            if ($commune->region_id != $validated['region_id']) {
                throw ValidationException::withMessages([
                    'commune_id' => 'La comuna seleccionada no pertenece a la región especificada.'
                ]);
            }

            // Remover region_id de los datos a insertar
            $dataToInsert = [
                'rbd' => $validated['rbd'],
                'name' => $validated['name'],
                'address' => $validated['address'],
                'commune_id' => $validated['commune_id'],
                'pie_quota_max' => $validated['pie_quota_max'],
                'is_active' => $validated['is_active'],
            ];

            $establishment = Establishment::create($dataToInsert);
            $establishment->load(['commune.region']);

            Log::info('Establecimiento creado con éxito', [
                'id' => $establishment->id,
                'datos' => $dataToInsert,
                'establishment_with_relations' => $establishment->toArray()
            ]);

            return redirect()->route('establishments.index')
                ->with([
                    'success' => 'Establecimiento creado correctamente',
                    'newEstablishment' => [
                        'id' => $establishment->id,
                        'rbd' => $establishment->rbd,
                        'name' => $establishment->name,
                        'address' => $establishment->address,
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
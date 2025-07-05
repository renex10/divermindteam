<?php
/* ruta del archivo es app\Http\Controllers\Establishment\EstablishmentController.php */
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
    public function index(Request $request)
    {
        // 🔍 Obtener el término de búsqueda del request
        $search = $request->get('search', '');
        
        // 🏗️ Construir la consulta base
        $query = Establishment::with('commune.region')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
        
        // 🔍 Aplicar filtros de búsqueda si hay término de búsqueda
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('rbd', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%')
                  // También buscar en relaciones
                  ->orWhereHas('commune', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('commune.region', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // 📄 Paginar los resultados
        $establishments = $query->paginate(8);
        
        // 🌍 Obtener regiones y comunas para los filtros
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
            // 🔍 Enviar el término de búsqueda actual al frontend
            'filters' => [
                'search' => $search,
            ],
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

            // 🆕 MODIFICACIÓN: Devolver datos completos del establecimiento recién creado
            return redirect()->route('establishments.index')
                ->with([
                    'success' => 'Establecimiento creado correctamente',
                    'newEstablishment' => new EstablishmentResource($establishment) // Usar el Resource para consistencia
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
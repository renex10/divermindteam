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
use App\Http\Requests\Establishment\UpdateEstablishmentRequest;

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
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function update(UpdateEstablishmentRequest $request, Establishment $establishment)
    {
        try {
            // 🔍 LOG DETALLADO: Información de la petición
            Log::info('🚀 INICIO UPDATE ESTABLISHMENT', [
                'timestamp' => now(),
                'establishment_id' => $establishment->id,
                'request_method' => $request->method(),
                'request_url' => $request->url(),
                'request_route' => $request->route()->getName(),
                'request_params' => $request->route()->parameters(),
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // 🔍 LOG: Datos recibidos
            Log::info('📥 DATOS RECIBIDOS', [
                'raw_input' => $request->all(),
                'validated_data' => $request->validated(),
                'has_files' => $request->hasFile('any'),
                'content_type' => $request->header('Content-Type'),
                'accept_header' => $request->header('Accept')
            ]);

            // 🔍 LOG: Estado antes de la actualización
            Log::info('📋 ESTADO ANTES DE ACTUALIZAR', [
                'establishment_before' => $establishment->toArray(),
                'commune_before' => $establishment->commune ? $establishment->commune->toArray() : null,
                'region_before' => $establishment->commune && $establishment->commune->region ? 
                    $establishment->commune->region->toArray() : null
            ]);

            // 🔄 Actualizar el establecimiento
            $updateResult = $establishment->update($request->validated());
            
            // 🔍 LOG: Resultado de la actualización
            Log::info('✅ RESULTADO UPDATE', [
                'update_success' => $updateResult,
                'establishment_after' => $establishment->fresh()->toArray()
            ]);

            // 🔄 Cargar relaciones actualizadas
            $establishment->load(['commune.region']);

            // 🔍 LOG: Datos después de cargar relaciones
            Log::info('🔗 RELACIONES CARGADAS', [
                'establishment_with_relations' => $establishment->toArray(),
                'commune_loaded' => $establishment->commune ? $establishment->commune->toArray() : null,
                'region_loaded' => $establishment->commune && $establishment->commune->region ? 
                    $establishment->commune->region->toArray() : null
            ]);

            // 🔍 LOG: Preparando respuesta
            Log::info('📤 PREPARANDO RESPUESTA', [
                'redirect_route' => 'establishments.index',
                'success_message' => 'Establecimiento actualizado correctamente',
                'response_type' => 'redirect'
            ]);

            // ✅ Respuesta exitosa
            $response = redirect()->route('establishments.index')
                ->with('success', 'Establecimiento actualizado correctamente');

            Log::info('🎉 UPDATE COMPLETADO EXITOSAMENTE', [
                'establishment_id' => $establishment->id,
                'timestamp' => now(),
                'execution_time' => microtime(true) - LARAVEL_START
            ]);

            return $response;

        } catch (ValidationException $e) {
            // 🚨 LOG: Errores de validación
            Log::error('❌ ERROR DE VALIDACIÓN', [
                'establishment_id' => $establishment->id,
                'validation_errors' => $e->errors(),
                'failed_rules' => $e->validator->failed(),
                'input_data' => $request->all()
            ]);

            return back()->withErrors($e->errors())->withInput();

        } catch (Exception $e) {
            // 🚨 LOG: Error general
            Log::error('💥 ERROR CRÍTICO EN UPDATE', [
                'establishment_id' => $establishment->id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'request_method' => $request->method(),
                'request_url' => $request->url(),
                'timestamp' => now()
            ]);
            
            return back()->withErrors(['error' => 'Error al actualizar el establecimiento: ' . $e->getMessage()]);
        }
    }

    public function edit(Establishment $establishment)
    {
        // 🔍 LOG: Carga de formulario de edición
        Log::info('📝 CARGANDO FORMULARIO EDICIÓN', [
            'establishment_id' => $establishment->id,
            'establishment_data' => $establishment->toArray(),
            'timestamp' => now()
        ]);

        $establishment->load('commune.region');

        return Inertia::render('Dashboard/establishments/Edit', [
            'establishment' => new EstablishmentResource($establishment),
            'regiones' => RegionResource::collection(Region::all()),
            'comunas' => CommuneResource::collection(Commune::all())
        ]);
    }

    // 🔍 Método adicional para debugging de rutas
    public function debugRoutes(Request $request)
    {
        Log::info('🔍 DEBUG RUTAS', [
            'all_routes' => collect(\Route::getRoutes())->map(function ($route) {
                return [
                    'methods' => $route->methods(),
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'action' => $route->getActionName()
                ];
            })->filter(function ($route) {
                return str_contains($route['uri'], 'establishment');
            })->toArray()
        ]);

        return response()->json(['message' => 'Debug info logged']);
    }
}
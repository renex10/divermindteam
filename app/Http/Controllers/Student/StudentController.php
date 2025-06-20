<?php

namespace App\Http\Controllers\Student;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Inertia\Inertia;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use Illuminate\Support\Facades\Schema;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Obtener parámetros de paginación desde la request
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);

        // Consulta optimizada con relaciones y ordenamiento
        $studentsQuery = Student::with(['user' => function ($query) {
            $query->orderBy('last_name', 'asc')
                ->orderBy('name', 'asc');
        }])
            ->orderBy('priority', 'asc')
            ->orderBy('created_at', 'desc');

        // Obtener datos paginados
        $students = $studentsQuery->paginate($perPage, ['*'], 'page', $page);
        $students->appends($request->query());

        // Transformar datos usando StudentResource manteniendo la paginación
        $transformedStudents = [
            'data' => StudentResource::collection($students->items()),
            'current_page' => $students->currentPage(),
            'last_page' => $students->lastPage(),
            'per_page' => $students->perPage(),
            'total' => $students->total(),
            'from' => $students->firstItem(),
            'to' => $students->lastItem(),
            'path' => $students->path(),
            'links' => $students->linkCollection()
        ];

        // AGREGAR: Obtener cursos para el formulario
        $courses = Course::orderBy('name')->get();
        
        // DEBUG: Log para verificar cursos
        Log::debug('Cursos en index:', [
            'count' => $courses->count(),
            'courses' => $courses->toArray()
        ]);

        // Retornar vista con datos transformados, paginados Y cursos
        return Inertia::render('Student', [
            'students' => $transformedStudents,
            'courses' => $courses, // AGREGAR esta línea
            'specialists' => [], // Agregar si tienes especialistas
        ]);
    }

    public function create()
    {
        try {
            // Verificar conexión a la base de datos
            DB::connection()->getPdo();
            Log::debug('Conexión a la base de datos exitosa');

            // Verificar existencia de la tabla
            $tableExists = Schema::hasTable('courses');
            Log::debug('Tabla courses existe: ' . ($tableExists ? 'Sí' : 'No'));

            // Obtener cursos
            $courses = Course::orderBy('name')->get();
            
            Log::debug('Cursos obtenidos en create:', [
                'count' => $courses->count(),
                'courses' => $courses->toArray()
            ]);
            
            // Si no hay cursos, crear uno de prueba
            if ($courses->isEmpty()) {
                Log::warning('No se encontraron cursos - creando uno de prueba');
                
                try {
                    $testCourse = Course::create([
                        'name' => 'Curso de Prueba',
                        // Agregar otros campos requeridos si los hay
                    ]);
                    $courses = Course::orderBy('name')->get();
                    Log::debug('Curso de prueba creado', $testCourse->toArray());
                } catch (\Exception $e) {
                    Log::error('Error al crear curso de prueba', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Estructura de estudiantes vacía para vista de creación
            $emptyStudents = [
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 10,
                'total' => 0,
                'from' => 0,
                'to' => 0,
                'path' => '',
                'links' => []
            ];

            return Inertia::render('Student', [
                'students' => $emptyStudents,
                'courses' => $courses,
                'specialists' => [], // Agregar especialistas si los tienes
                'isCreateView' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en StudentController::create', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Inertia::render('Error', [
                'message' => 'Error al cargar los cursos: ' . $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        // Agregar método store si no existe
        try {
            // Validar datos
            $validated = $request->validate([
                'course_id' => 'required|exists:courses,id',
                // Agregar otras validaciones necesarias
            ]);

            // Crear estudiante
            $student = Student::create($validated);

            Log::info('Estudiante creado exitosamente:', $student->toArray());

            return redirect()->route('students.index')
                ->with('success', 'Estudiante creado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al crear estudiante:', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return back()->withErrors(['error' => 'Error al crear estudiante: ' . $e->getMessage()]);
        }
    }
}
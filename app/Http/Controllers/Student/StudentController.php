<?php

namespace App\Http\Controllers\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Inertia\Inertia;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Professional;
use App\Models\UserDocument;
use App\Models\User; // Importar modelo User

class StudentController extends Controller
{
    /**
     * Muestra el listado paginado de estudiantes
     * 
     * @param Request $request
     * @return \Inertia\Response
     */
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

        // Obtener cursos para el formulario
        $courses = Course::orderBy('name')->get();
        
        // Obtener especialistas activos con sus datos de usuario
        $specialists = Professional::with(['user:id,name,last_name', 'specialty:id,name'])
            ->where('active', true)
            ->get()
            ->map(function ($professional) {
                return [
                    'id' => $professional->id,
                    'name' => $professional->user->name,
                    'last_name' => $professional->user->last_name,
                    'specialty' => $professional->specialty->name,
                    'full_name' => $professional->user->name . ' ' . $professional->user->last_name
                ];
            });

        // Obtener usuarios con su RUT para el selector
        $users = User::with('document')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'rut' => $user->document->rut ?? 'Sin RUT'
                ];
            });

        // Debug: Verificar datos obtenidos
        Log::debug('Datos para vista Student:', [
            'students_count' => $students->total(),
            'courses_count' => $courses->count(),
            'specialists_count' => $specialists->count(),
            'users_count' => $users->count(),
            'first_user' => $users->first()
        ]);

        // Retornar vista con todos los datos necesarios
        return Inertia::render('Student', [
            'students' => $transformedStudents,
            'courses' => $courses,
            'specialists' => $specialists,
            'users' => $users // 👈 Enviar usuarios formateados
        ]);
    }

    /**
     * Muestra el formulario de creación de estudiantes
     * 
     * @return \Inertia\Response
     */
    public function create()
    {
        try {
            // Verificar conexión a la base de datos
            DB::connection()->getPdo();
            Log::debug('Conexión a la base de datos exitosa');

            // Verificar existencia de tablas necesarias
            $coursesTableExists = Schema::hasTable('courses');
            $professionalsTableExists = Schema::hasTable('professionals');
            $usersTableExists = Schema::hasTable('users');
            Log::debug('Tablas existentes:', [
                'courses' => $coursesTableExists,
                'professionals' => $professionalsTableExists,
                'users' => $usersTableExists
            ]);

            // Obtener cursos
            $courses = Course::orderBy('name')->get();
            
            // Obtener especialistas activos
            $specialists = [];
            if ($professionalsTableExists) {
                $specialists = Professional::with(['user:id,name,last_name', 'specialty:id,name'])
                    ->where('active', true)
                    ->get()
                    ->map(function ($professional) {
                        return [
                            'id' => $professional->id,
                            'name' => $professional->user->name,
                            'last_name' => $professional->user->last_name,
                            'specialty' => $professional->specialty->name,
                            'full_name' => $professional->user->name . ' ' . $professional->user->last_name
                        ];
                    });
            }

            // Obtener usuarios con su RUT
            $users = [];
            if ($usersTableExists) {
                $users = User::with('document')
                    ->get()
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'last_name' => $user->last_name,
                            'rut' => $user->document->rut ?? 'Sin RUT'
                        ];
                    });
            }

            Log::debug('Datos obtenidos en create:', [
                'courses_count' => $courses->count(),
                'specialists_count' => count($specialists),
                'users_count' => count($users),
                'first_user' => $users[0] ?? null
            ]);
            
            // Si no hay cursos, crear uno de prueba
            if ($courses->isEmpty()) {
                Log::warning('No se encontraron cursos - creando uno de prueba');
                
                try {
                    $testCourse = Course::create([
                        'name' => 'Curso de Prueba',
                        'level' => 'Básico',
                        'parallel' => 'A'
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
                'path' => route('students.index'),
                'links' => []
            ];

            return Inertia::render('Student', [
                'students' => $emptyStudents,
                'courses' => $courses,
                'specialists' => $specialists,
                'users' => $users, // 👈 Usuarios incluidos
                'isCreateView' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en StudentController::create', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Inertia::render('Error', [
                'message' => 'Error al cargar los datos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Almacena un nuevo estudiante en la base de datos
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
public function store(Request $request)
{
    DB::beginTransaction();

    try {
        // Decodificar new_user si viene como JSON (opcional)
        if ($request->has('new_user') && is_string($request->input('new_user'))) {
            $newUserData = json_decode($request->input('new_user'), true);
            $request->merge(['new_user' => $newUserData]);
        }

        // Validación de campos
        $validated = $request->validate([
            // Nuevo usuario
            'new_user.name' => 'required|string|max:255',
            'new_user.last_name' => 'required|string|max:255',
            'new_user.email' => 'required|email|unique:users,email',
            'new_user.password' => 'required|string|min:8',

            // Datos comunes del estudiante
            'birth_date' => 'required|date',
            'course_id' => 'required|exists:courses,id',
            'diagnosis' => 'nullable|string',
            'guardian_email' => 'nullable|email',

            // Documentos
            'medical_report' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'previous_reports.*' => 'nullable|file|mimes:pdf,doc,docx|max:5120',

            // Clasificación
            'need_type' => 'required|in:permanent,temporary',
            'priority' => 'required|integer|min:1|max:3',
            'special_needs' => 'nullable|string',

            // Consentimientos
            'consent_pie' => 'required|boolean',
            'data_processing' => 'required|boolean',
            'guardian_name' => 'required_if:consent_pie,1|string|max:255',
            'guardian_rut' => 'required_if:consent_pie,1|string|max:20',

            // Asignación
            'assigned_specialist' => 'required|exists:professionals,id',
            'evaluation_date' => 'required|date',
            'initial_observations' => 'nullable|string',
        ]);

        // Crear nuevo usuario
        $user = User::create([
            'name' => $validated['new_user']['name'],
            'last_name' => $validated['new_user']['last_name'],
            'email' => $validated['new_user']['email'],
            'password' => Hash::make($validated['new_user']['password']),
            'establishment_id' => Auth::user()->establishment_id,
        ]);

        // Log: usuario creado
        Log::info('Usuario creado exitosamente', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);

        $rut = 'SIN-RUT-' . $user->id;

        // Guardar archivos
        $medicalReportPath = $request->file('medical_report')->store('medical_reports');
        $previousReportsPaths = [];

        if ($request->hasFile('previous_reports')) {
            foreach ($request->file('previous_reports') as $file) {
                $previousReportsPaths[] = $file->store('previous_reports');
            }
        }

        // Crear estudiante
        $student = Student::create([
            'user_id' => $user->id,
            'rut' => $rut,
            'birth_date' => $validated['birth_date'],
            'course_id' => $validated['course_id'],
            'diagnosis' => $validated['diagnosis'] ?? null,
            'guardian_email' => $validated['guardian_email'] ?? null,
            'medical_report_path' => $medicalReportPath,
            'previous_reports_paths' => json_encode($previousReportsPaths),
            'need_type' => $validated['need_type'],
            'priority' => $validated['priority'],
            'special_needs' => $validated['special_needs'] ?? null,
            'consent_pie' => $validated['consent_pie'] ? 1 : 0,
            'data_processing' => $validated['data_processing'] ? 1 : 0,
            'guardian_name' => $validated['guardian_name'] ?? null,
            'guardian_rut' => $validated['guardian_rut'] ?? null,
            'assigned_specialist' => $validated['assigned_specialist'],
            'evaluation_date' => $validated['evaluation_date'],
            'initial_observations' => $validated['initial_observations'] ?? null,
            'created_by' => Auth::id(),
        ]);

        DB::commit();

        return redirect()->route('students.index')->with('success', 'Estudiante creado exitosamente');
    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error al crear estudiante', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);

        return back()->withErrors([
            'error' => 'Error al crear estudiante: ' . $e->getMessage()
        ]);
    }
}
}
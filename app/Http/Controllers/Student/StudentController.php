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
use App\Models\GuardianStudent;
use App\Models\Consent;
use App\Models\Document;
use Illuminate\Support\Str;

use App\Http\Requests\Student\StoreStudentRequest;

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

    // Consulta optimizada con relaciones y nuevo ordenamiento
    $studentsQuery = Student::with(['user' => function ($query) {
            $query->select('id', 'name', 'last_name', 'email'); // Solo campos necesarios
        }])
        ->orderBy('created_at', 'desc')  // Primero: últimos creados primero
        ->orderBy('priority', 'asc');    // Segundo: prioridad más alta

    // Obtener datos paginados
    $students = $studentsQuery->paginate($perPage, ['*'], 'page', $page);
    $students->appends($request->query());

    // Transformar datos usando StudentResource
    $transformedStudents = [
        'data' => StudentResource::collection($students->items()),
        'current_page' => $students->currentPage(),
        'last_page' => $students->lastPage(),
        'per_page' => $students->perPage(),
        'total' => $students->total(),
        'from' => $students->firstItem(),
        'to' => $students->lastItem(),
        'path' => $students->path(),
        'links' => $students->linkCollection()->toArray()
    ];

    // Obtener cursos (optimizado)
    $courses = Course::orderBy('name')->get(['id', 'name']);

    // Obtener especialistas activos (optimizado)
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

    // Obtener usuarios con RUT (optimizado)
    $users = User::with('document:id,user_id,rut')
        ->get(['id', 'name', 'last_name'])
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
        'last_student' => $students->first() ? $students->first()->created_at : null,
        'courses_count' => $courses->count(),
        'specialists_count' => $specialists->count(),
        'users_count' => $users->count()
    ]);

    // Retornar vista con todos los datos necesarios
    return Inertia::render('Student', [
        'students' => $transformedStudents,
        'courses' => $courses,
        'specialists' => $specialists,
        'users' => $users
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
public function store(StoreStudentRequest $request)
{
    DB::beginTransaction();

    try {
        $validated = $request->validated();
        $establishmentId = Auth::user()->establishment_id;

        // 1. Crear usuario para el estudiante
        $user = User::create([
            'name' => $validated['new_user']['name'],
            'last_name' => $validated['new_user']['last_name'],
            'email' => $validated['new_user']['email'],
            'password' => Hash::make($validated['new_user']['password']),
            'establishment_id' => $establishmentId,
        ]);

        // 2. Crear documento RUT para el ESTUDIANTE (usando un placeholder único)
        UserDocument::updateOrCreate(
            ['user_id' => $user->id],
            [
                'rut' => 'STUDENT-' . $user->id, // RUT único basado en ID
                'verified' => false
            ]
        );

        // 3. Crear estudiante
        $student = Student::create([
            'user_id' => $user->id,
            'establishment_id' => $user->establishment_id,
            'birth_date' => $validated['birth_date'],
            'diagnosis' => $validated['diagnosis'] ?? null,
            'need_type' => $validated['need_type'],
            'priority' => $validated['priority'],
            'special_needs' => $validated['special_needs'] ?? null,
            'assigned_specialist_id' => $validated['assigned_specialist_id'],
            'evaluation_date' => $validated['evaluation_date'],
            'initial_observations' => $validated['initial_observations'] ?? null,
        ]);

        // 4. Asignar curso
        $student->courses()->attach($validated['course_id']);

        // 5. Manejar apoderados y consentimientos
        if ($validated['consent_pie']) {
            // Buscar o crear usuario para el apoderado
            $guardianUser = User::firstOrCreate(
                ['email' => $validated['guardian_email']],
                [
                    'name' => $validated['guardian_name'],
                    'password' => Hash::make(Str::random(40)),
                    'establishment_id' => $establishmentId,
                ]
            );

            // Crear documento RUT para APODERADO (usando el RUT proporcionado)
            UserDocument::updateOrCreate(
                ['user_id' => $guardianUser->id],
                ['rut' => $validated['guardian_rut'], 'verified' => false]
            );

            // Crear relación apoderado-estudiante
            $guardianStudent = GuardianStudent::create([
                'student_id' => $student->id,
                'guardian_id' => $guardianUser->id,
                'establishment_id' => $establishmentId,
                'is_primary' => true,
                'relationship' => $validated['relationship'],
            ]);

            // Crear consentimiento
            Consent::create([
                'guardian_student_id' => $guardianStudent->id,
                'establishment_id' => $establishmentId,
                'consent_pie' => true,
                'data_processing' => $validated['data_processing'],
                'signed_at' => now(),
            ]);
        }

        // 6. Guardar documentos médicos
        $this->storeDocuments($request, $student);

        DB::commit();

        return redirect()->route('students.index')->with('success', 'Estudiante creado exitosamente');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error creando estudiante', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return back()->withInput()->withErrors('Error: ' . $e->getMessage());
    }
}
private function storeDocuments($request, $student)
{
    // Documento médico principal
    $medicalReport = $request->file('medical_report');
    Document::create([
        'documentable_type' => Student::class,
        'documentable_id' => $student->id,
        'type' => 'medical_report',
        'path' => $medicalReport->store("establishments/{$student->establishment_id}/medical_reports"),
        'format' => $medicalReport->extension(),
        'description' => 'Informe médico inicial',
    ]);

    // Reportes anteriores (opcionales)
    if ($request->hasFile('previous_reports')) {
        foreach ($request->file('previous_reports') as $file) {
            Document::create([
                'documentable_type' => Student::class,
                'documentable_id' => $student->id,
                'type' => 'previous_report',
                'path' => $file->store("establishments/{$student->establishment_id}/previous_reports"),
                'format' => $file->extension(),
                'description' => 'Reporte médico anterior',
            ]);
        }
    }
}


public function stats()
{
    $establishmentId = Auth::user()->establishment_id;
    
    return response()->json([
        'total' => Student::where('establishment_id', $establishmentId)->count(),
        'active' => Student::where('establishment_id', $establishmentId)
                         ->where('active', true)->count(),
        'high_priority' => Student::where('establishment_id', $establishmentId)
                               ->where('priority', 1)->count(),
    ]);
}


public function destroy(Student $student)
{
    try {
        DB::transaction(function () use ($student) {
            // Eliminar documentos asociados
            $student->user->document()->delete();
            
            // Eliminar relaciones
            $student->courses()->detach();
            
            // Eliminar usuario asociado
            $student->user()->delete();
            
            // Finalmente eliminar el estudiante
            $student->delete();
        });
        
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
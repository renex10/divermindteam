<?php

namespace App\Http\Controllers\Student;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Inertia\Inertia;
use App\Http\Resources\StudentResource;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\UserDocument;
use Illuminate\Support\Str;
use App\Models\GuardianStudent;
use App\Models\Consent;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Obtener parámetros de paginación desde la request
        $perPage = $request->input('perPage', 10); // Por defecto 10 por página
        $page = $request->input('page', 1);        // Página actual

        // Consulta optimizada con relaciones y ordenamiento
        $studentsQuery = Student::with(['user' => function ($query) {
            // Ordenar por apellido dentro de la relación
            $query->orderBy('last_name', 'asc')
                ->orderBy('name', 'asc');
        }])
            ->orderBy('priority', 'asc')      // Prioridad ascendente (1, 2, 3)
            ->orderBy('created_at', 'desc');  // Más recientes primero como segundo criterio

        // Obtener datos paginados
        $students = $studentsQuery->paginate($perPage, ['*'], 'page', $page);

        // Agregar información de la query a la URL para mantener parámetros
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

        // Logging para debugging (remover en producción)
        Log::info('Students data structure:', [
            'total' => $students->total(),
            'per_page' => $students->perPage(),
            'current_page' => $students->currentPage(),
            'last_page' => $students->lastPage(),
            'data_count' => $students->count()
        ]);

        // Retornar vista con datos transformados y paginados
        return Inertia::render('Student', [
            'students' => $transformedStudents
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    // Función auxiliar para convertir valores a booleanos
    private function convertToBoolean($value)
    {
        if ($value === 'true' || $value === '1' || $value === 1) {
            return true;
        } elseif ($value === 'false' || $value === '0' || $value === 0) {
            return false;
        }
        return (bool) $value;
    }

 public function store(Request $request)
{
    Log::info('Inicio del método store para crear estudiante');

    // Verificar autenticación del usuario
    $authUser = Auth::user();
    if (!$authUser) {
        Log::error('Usuario no autenticado intentando crear estudiante');
        return back()->with('error', 'Usuario no autenticado');
    }

    $establishmentId = $authUser->establishment_id;
    Log::info('Establecimiento ID: ' . $establishmentId);
    
    // SOLUCIÓN: Mejorar logging para ver datos reales recibidos
    Log::info('Datos recibidos en la solicitud (raw):', [
        'inputs' => $request->all(),
        'files' => [
            'medical_report' => $request->hasFile('medical_report') ? 'present' : 'absent',
            'previous_reports' => $request->hasFile('previous_reports') ? count($request->file('previous_reports')) : 0
        ]
    ]);

    // SOLUCIÓN: Validar archivos de manera más robusta
    $hasValidMedicalReport = $request->hasFile('medical_report') && $request->file('medical_report')->isValid();
    $hasValidPreviousReports = $request->hasFile('previous_reports');
    
    // SOLUCIÓN: Manejar campos booleanos y numéricos de forma consistente
    $requestData = $request->all();
    
    // Convertir valores a tipos correctos
    $requestData = array_map(function ($value) {
        if ($value === 'true') return true;
        if ($value === 'false') return false;
        if ($value === '1') return 1;
        if ($value === '0') return 0;
        return $value;
    }, $requestData);

    // Asignar conversiones específicas
    if (isset($requestData['consent_pie'])) {
        $requestData['consent_pie'] = (bool) $requestData['consent_pie'];
    }
    
    if (isset($requestData['data_processing'])) {
        $requestData['data_processing'] = (bool) $requestData['data_processing'];
    }

    if (isset($requestData['assigned_specialist'])) {
        $requestData['assigned_specialist'] = (int) $requestData['assigned_specialist'];
    }

    if (isset($requestData['priority'])) {
        $requestData['priority'] = (int) $requestData['priority'];
    }

    // Reemplazar los datos de la request
    $request->replace($requestData);
    Log::info('Datos procesados:', $request->all());

    // SOLUCIÓN: Validación mejorada con manejo de tipos
    try {
        Log::info('Validando datos del formulario');
        $validationRules = [
            // Datos personales básicos
            'full_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'rut' => 'required|string|max:12|unique:user_documents,rut',
            'birth_date' => 'required|date',
            'course_id' => 'required|integer|exists:courses,id', // Cambiado a course_id
            'diagnosis' => 'nullable|string',
            'guardian_email' => 'nullable|email',
            
            // Datos de clasificación
            'need_type' => 'required|in:permanent,temporary',
            'priority' => 'required|integer|in:1,2,3',
            'special_needs' => 'nullable|string',
            
            // Consentimientos y datos de apoderado
            'consent_pie' => 'required|boolean',
            'data_processing' => 'required|boolean',
            'guardian_name' => 'required_if:consent_pie,true|string|max:255',
            'guardian_rut' => 'required_if:consent_pie,true|string|max:12|unique:user_documents,rut',
            
            // Asignación de profesional - VERIFICAR QUE EXISTE Y ESTÁ ACTIVO
        'assigned_specialist' => 'required|integer|exists:professionals,id,active,1',
            'evaluation_date' => 'required|date',
            'initial_observations' => 'nullable|string',
            
            // Archivos - SOLUCIÓN: Hacer requerido el informe médico
           'medical_report' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ];

        // Validación condicional para archivos
if ($request->hasFile('medical_report')) {
    $validationRules['medical_report'] = 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120';
}

if ($request->hasFile('previous_reports')) {
    $validationRules['previous_reports.*'] = 'file|mimes:pdf,doc,docx|max:5120';
}

        // SOLUCIÓN: Validación condicional para archivos múltiples
        if ($hasValidPreviousReports) {
            $validationRules['previous_reports.*'] = 'file|mimes:pdf,doc,docx|max:5120';
        }

        $validated = $request->validate($validationRules);
        
        Log::info('Validación exitosa', $validated);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Error de validación: ' . json_encode($e->errors()));
        return back()->withErrors($e->errors())->withInput();
    }

    // Iniciar transacción de base de datos
    DB::beginTransaction();
    Log::info('Inicio de transacción');

    try {
        // Crear usuario para el estudiante
        Log::info('Creando usuario para el estudiante');
        $userStudent = User::create([
            'name' => $validated['full_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['guardian_email'] ?? null,
            'password' => bcrypt(Str::random(16)),
            'establishment_id' => $establishmentId,
        ]);
        Log::info('Usuario estudiante creado: ' . $userStudent->id);

        // Crear documento de identidad del estudiante
        Log::info('Creando documento del estudiante');
        $studentDocument = UserDocument::create([
            'user_id' => $userStudent->id,
            'rut' => $validated['rut'],
            'verified' => true,
        ]);
        Log::info('Documento estudiante creado: ' . $studentDocument->id);

        // Crear registro principal del estudiante
        Log::info('Creando registro de estudiante');
        $student = Student::create([
            'user_id' => $userStudent->id,
            'document_id' => $studentDocument->id,
            'course_id' => (int) $validated['course_id'],
            'birth_date' => $validated['birth_date'],
            'need_type' => $validated['need_type'],
            'diagnosis' => $validated['diagnosis'],
            'priority' => $validated['priority'],
            'active' => true,
        ]);
        Log::info('Estudiante creado: ' . $student->id);

        // Manejo de apoderado y consentimientos
        if ($validated['consent_pie']) {
            Log::info('Creando apoderado');
            
            $userGuardian = User::create([
                'name' => $validated['guardian_name'],
                'last_name' => '', // SOLUCIÓN: Añadir campo requerido
                'email' => $validated['guardian_email'],
                'password' => bcrypt(Str::random(16)),
                'establishment_id' => $establishmentId,
            ]);
            Log::info('Usuario apoderado creado: ' . $userGuardian->id);

            // Crear documento del apoderado
            UserDocument::create([
                'user_id' => $userGuardian->id,
                'rut' => $validated['guardian_rut'],
                'verified' => true,
            ]);

            // Relación apoderado-estudiante
            $guardianStudent = GuardianStudent::create([
                'student_id' => $student->id,
                'guardian_id' => $userGuardian->id,
                'is_primary' => true,
                'relationship' => 'Apoderado',
            ]);
            Log::info('Relación apoderado-estudiante creada: ' . $guardianStudent->id);

            // Registrar consentimientos
            Consent::create([
                'guardian_student_id' => $guardianStudent->id,
                'consent_pie' => $validated['consent_pie'],
                'data_processing' => $validated['data_processing'],
                'signed_at' => now(),
            ]);
        }

        // Asignar profesional
        Log::info('Asignando profesional');
        DB::table('student_professional_assignments')->insert([
            'student_id' => $student->id,
            'professional_id' => $validated['assigned_specialist'],
            'evaluation_date' => $validated['evaluation_date'],
            'observations' => $validated['initial_observations'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Log::info('Profesional asignado');

        // Manejar documentos adjuntos
        Log::info('Manejando documentos adjuntos');
        
        // SOLUCIÓN: Manejar informe médico
        if ($request->hasFile('medical_report')) {
            Log::info('Procesando medical_report');
            $this->storeDocument(
                $request->file('medical_report'),
                $student,
                'medical_report',
                'Informe médico'
            );
        }

        // SOLUCIÓN: Manejar informes previos
        if ($request->hasFile('previous_reports')) {
            $files = $request->file('previous_reports');
            Log::info('Procesando ' . count($files) . ' informes previos');
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $this->storeDocument(
                        $file,
                        $student,
                        'previous_report',
                        'Informe previo'
                    );
                }
            }
        }

        DB::commit();
        Log::info('Transacción completada con éxito');

        return redirect()->route('students.index')
            ->with('success', 'Estudiante creado exitosamente');
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error durante la transacción: ' . $e->getMessage());
        Log::error($e->getTraceAsString());

        return back()
            ->with('error', 'Error al crear estudiante: ' . $e->getMessage())
            ->withInput();
    }
}

private function storeDocument($file, $student, $type, $description)
{
    try {
        Log::info("Guardando documento: $type");
        
        // SOLUCIÓN: Usar almacenamiento con nombre único
        $path = $file->store('documents', 'public');
        
        // SOLUCIÓN: Obtener extensión real
        $extension = $file->getClientOriginalExtension();
        
        Log::info("Archivo almacenado en: $path");
        
        Document::create([
            'documentable_id' => $student->id,
            'documentable_type' => Student::class,
            'type' => $type,
            'path' => $path,
            'format' => $extension,
            'description' => $description,
            'original_name' => $file->getClientOriginalName(), // SOLUCIÓN: Guardar nombre original
        ]);
    } catch (\Exception $e) {
        Log::error("Error guardando documento $type: " . $e->getMessage());
        throw $e;
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
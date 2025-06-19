<?php

namespace App\Http\Controllers\Student;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Inertia\Inertia;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;

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

}
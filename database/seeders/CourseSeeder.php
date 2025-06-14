<?php

namespace Database\Seeders;

use App\Models\Establishment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar prerequisitos
        if (Establishment::count() == 0) {
            $this->command->error('❌ Primero ejecute EstablishmentSeeder!');
            return;
        }

        if (User::count() == 0) {
            $this->command->error('❌ Primero ejecute UserSeeder!');
            return;
        }

        $this->command->info('🏫 Iniciando creación de cursos con límites...');

        DB::beginTransaction();

        try {
            // LÍMITES ESTRICTOS PARA EVITAR BUCLES INFINITOS
            $establishments = Establishment::limit(5)->get(); // MÁXIMO 5 establecimientos
            $teacherIds = User::limit(30)->pluck('id')->toArray(); // MÁXIMO 30 profesores
            
            if (empty($teacherIds)) {
                $this->command->error('❌ No hay usuarios disponibles para asignar como profesores');
                return;
            }

            $totalCoursesCreated = 0;
            $maxTotalCourses = 100; // LÍMITE ABSOLUTO DE CURSOS

            foreach ($establishments as $establishment) {
                $this->command->info("Creando cursos para: {$establishment->name}");
                
                $coursesForThisEstablishment = $this->createCoursesForEstablishment(
                    $establishment, 
                    $teacherIds, 
                    $maxTotalCourses - $totalCoursesCreated
                );
                
                $totalCoursesCreated += $coursesForThisEstablishment;
                
                // Verificar límite global
                if ($totalCoursesCreated >= $maxTotalCourses) {
                    $this->command->warn("⚠️ Límite máximo de cursos alcanzado ({$maxTotalCourses})");
                    break;
                }
            }

            DB::commit();
            $this->command->info("✅ {$totalCoursesCreated} cursos creados en " . count($establishments) . " establecimientos.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Crea cursos para un establecimiento específico con límites estrictos
     */
    private function createCoursesForEstablishment(Establishment $establishment, array $teacherIds, int $remainingQuota): int
    {
        $coursesCreated = 0;
        $maxCoursesPerEstablishment = min(20, $remainingQuota); // MÁXIMO 20 por establecimiento

        // Estructura FIJA de cursos (sin aleatoriedad)
        $courseDefinitions = [
            // KINDER (solo 2 cursos)
            ['level' => 'kinder', 'name' => 'Kínder A', 'shift' => 'morning'],
            ['level' => 'kinder', 'name' => 'Kínder B', 'shift' => 'afternoon'],
            
            // BÁSICA (8 cursos, solo jornada mañana para limitar)
            ['level' => 'basic', 'name' => '1° Básico A', 'shift' => 'morning'],
            ['level' => 'basic', 'name' => '2° Básico A', 'shift' => 'morning'],
            ['level' => 'basic', 'name' => '3° Básico A', 'shift' => 'morning'],
            ['level' => 'basic', 'name' => '4° Básico A', 'shift' => 'morning'],
            ['level' => 'basic', 'name' => '5° Básico A', 'shift' => 'morning'],
            ['level' => 'basic', 'name' => '6° Básico A', 'shift' => 'morning'],
            ['level' => 'basic', 'name' => '7° Básico A', 'shift' => 'morning'],
            ['level' => 'basic', 'name' => '8° Básico A', 'shift' => 'morning'],
            
            // MEDIA (4 cursos)
            ['level' => 'high', 'name' => '1° Medio A', 'shift' => 'morning'],
            ['level' => 'high', 'name' => '2° Medio A', 'shift' => 'morning'],
            ['level' => 'high', 'name' => '3° Medio A', 'shift' => 'afternoon'],
            ['level' => 'high', 'name' => '4° Medio A', 'shift' => 'afternoon'],
        ];

        // Limitar la cantidad de cursos definidos
        $coursesToCreate = array_slice($courseDefinitions, 0, $maxCoursesPerEstablishment);

        foreach ($coursesToCreate as $courseData) {
            try {
                DB::table('courses')->insert([
                    'establishment_id' => $establishment->id,
                    'name' => $courseData['name'],
                    'level' => $courseData['level'],
                    'shift' => $courseData['shift'],
                    'teacher_id' => $this->assignTeacher($teacherIds),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $coursesCreated++;
                
                // Verificar límite por establecimiento
                if ($coursesCreated >= $maxCoursesPerEstablishment) {
                    break;
                }
                
            } catch (\Exception $e) {
                $this->command->warn("⚠️ Error creando curso {$courseData['name']}: " . $e->getMessage());
                continue;
            }
        }

        $this->command->info("  → {$coursesCreated} cursos creados para {$establishment->name}");
        return $coursesCreated;
    }

    /**
     * Asigna profesor jefe con validación
     */
    private function assignTeacher(array $teacherIds): ?int
    {
        if (empty($teacherIds)) {
            return null;
        }

        // 70% probabilidad de asignar profesor
        if (rand(1, 10) <= 7) {
            return $teacherIds[array_rand($teacherIds)];
        }
        
        return null;
    }

    /**
     * Método adicional para limpiar cursos existentes si es necesario
     */
    public function clearExistingCourses(): void
    {
        DB::table('course_students')->delete();
        DB::table('course_schedules')->delete(); 
        DB::table('courses')->delete();
        $this->command->info('🗑️ Cursos existentes eliminados');
    }
}
<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GuardianStudentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Verificar si la tabla existe
        if (!Schema::hasTable('guardian_students')) {
            $this->command->error('La tabla guardian_students no existe. Ejecute primero las migraciones.');
            return;
        }

        // 2. Verificar que haya estudiantes y usuarios
        if (Student::count() === 0 || User::count() === 0) {
            $this->command->error('Primero ejecute UserSeeder y StudentSeeder!');
            return;
        }

        DB::beginTransaction();

        try {
            $students = Student::all();
            $users = User::all();
            $relationships = ['padre', 'madre', 'tutor', 'abuelo', 'tío'];
            $relationsCreated = 0;

            foreach ($students as $student) {
                // Asignar 1-3 apoderados por estudiante
                $guardiansCount = rand(1, 3);
                $guardians = $users->random(min($guardiansCount, $users->count()));

                foreach ($guardians as $index => $guardian) {
                    // Verificar si la relación ya existe
                    $exists = DB::table('guardian_students')
                        ->where('student_id', $student->id)
                        ->where('guardian_id', $guardian->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('guardian_students')->insert([
                            'student_id' => $student->id,
                            'guardian_id' => $guardian->id,
                            'is_primary' => $index === 0,
                            'relationship' => $relationships[array_rand($relationships)],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $relationsCreated++;
                    }
                }
            }

            DB::commit();
            $this->command->info("Se crearon $relationsCreated relaciones apoderado-estudiante.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error al crear relaciones: ' . $e->getMessage());
        }
    }
}
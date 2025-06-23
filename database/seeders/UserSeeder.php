<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Establishment;
use App\Models\Professional;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurarse de que existan establecimientos antes de continuar
        if (Establishment::count() === 0) {
            $this->command->error('Primero debe ejecutar el EstablishmentSeeder!');
            return;
        }

        // Obtener un establecimiento válido aleatorio
        $establishment = Establishment::inRandomOrder()->first();

        // Crear o actualizar usuario real
        $rene = User::firstOrCreate(
            ['email' => 'reneprh2013@gmail.com'],
            [
                'name' => 'rene',
                'last_name' => 'riquelme',
                'password' => Hash::make('pangaleluney2013'),
                'email_verified_at' => now(),
                'establishment_id' => $establishment->id,
            ]
        );

        // Crear usuarios de prueba
        User::factory()
            ->count(10)
            ->create([
                'establishment_id' => $establishment->id,
            ]);

        // Crear cursos asociados al mismo establecimiento
        $courses = [
            ['name' => '1° Básico A', 'level' => 'basic', 'shift' => 'morning'],
            ['name' => '2° Básico B', 'level' => 'basic', 'shift' => 'afternoon'],
            ['name' => '3° Medio A', 'level' => 'high', 'shift' => 'morning'],
            ['name' => '4° Medio B', 'level' => 'high', 'shift' => 'afternoon'],
            ['name' => 'Prekínder A', 'level' => 'kinder', 'shift' => 'morning'],
        ];

        foreach ($courses as $data) {
            Course::firstOrCreate(
                [
                    'name' => $data['name'],
                    'establishment_id' => $establishment->id,
                ],
                [
                    'level' => $data['level'],
                    'shift' => $data['shift'],
                    'teacher_id' => null,
                ]
            );
        }

        // Crear especialidades si no existen
        $specialtyNames = ['Fonoaudiólogo', 'Psicopedagogo', 'Terapeuta Ocupacional', 'Psicólogo', 'Educador Diferencial', 'Coordinador PIE'];
        foreach ($specialtyNames as $name) {
            Specialty::firstOrCreate(
                ['name' => $name],
                ['description' => "Especialidad: $name"]
            );
        }

        $specialties = Specialty::all();

        // Asignar a Rene como profesional si aún no lo es
        $reneSpecialty = $specialties->firstWhere('name', 'Coordinador PIE') ?? $specialties->first();
        if (!$rene->professional) {
            Professional::create([
                'user_id' => $rene->id,
                'specialty_id' => $reneSpecialty->id,
                'establishment_id' => $establishment->id,
                'active' => true,
            ]);
        }

        // Crear 5 usuarios profesionales
        $professionalUsers = User::factory()
            ->count(5)
            ->create([
                'establishment_id' => $establishment->id,
            ]);

        foreach ($professionalUsers as $index => $user) {
            $specialty = $specialties[$index % $specialties->count()];

            Professional::create([
                'user_id' => $user->id,
                'specialty_id' => $specialty->id,
                'establishment_id' => $establishment->id,
                'active' => true,
            ]);
        }

        $this->command->info("Seeder ejecutado correctamente: usuarios, cursos y profesionales creados para el establecimiento '{$establishment->name}'");
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar prerequisitos
        if (User::count() == 0) {
            $this->command->error('Primero ejecute UserSeeder!');
            return;
        }

        if (DB::table('specialties')->count() == 0) {
            $this->command->error('Primero ejecute SpecialtySeeder!');
            return;
        }

        // Obtener especialidades disponibles
        $specialties = DB::table('specialties')->pluck('id')->toArray();

        // Asignar estado profesional (80% activos, 20% inactivos)
        $users = User::all();
        $professionalCount = 0;

        foreach ($users as $user) {
            // Solo crear registro si no existe
            if (!DB::table('professionals')->where('user_id', $user->id)->exists()) {
                DB::table('professionals')->insert([
                    'user_id' => $user->id,
                    'specialty_id' => $this->getRandomSpecialty($specialties),
                    'active' => $this->determineActiveStatus($user),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $professionalCount++;
            }
        }

        $this->command->info("{$professionalCount} registros profesionales creados (".round(($professionalCount/count($users))*100)."% de usuarios)");
    }

    /**
     * Asigna especialidad aleatoria
     */
    private function getRandomSpecialty(array $specialties): int
    {
        return $specialties[array_rand($specialties)];
    }

    /**
     * Determina estado activo (80% probabilidad activo)
     * Usuario rene siempre activo
     */
    private function determineActiveStatus(User $user): bool
    {
        if ($user->email === 'reneprh2013@gmail.com') {
            return true;
        }
        return rand(1, 100) <= 80;
    }
}
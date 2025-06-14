<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Professional;
use Illuminate\Support\Facades\DB;

class ProfessionalWorkHourSeeder extends Seeder
{
  /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si hay profesionales
        if (Professional::count() == 0) {
            $this->command->error('Primero ejecute ProfessionalSeeder!');
            return;
        }

        // Obtener solo profesionales activos (RF-3.5 priorización)
        $professionals = Professional::with('user')->active()->get();

        foreach ($professionals as $professional) {
            $weeklyHours = $this->determineWeeklyHours($professional);
            $availability = $this->generateAvailability($weeklyHours);

            DB::table('professional_work_hours')->updateOrInsert(
                ['professional_id' => $professional->id],
                [
                    'weekly_hours' => $weeklyHours,
                    'availability' => json_encode($availability),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info("Horarios asignados a {$professionals->count()} profesionales activos.");
    }

    /**
     * Determina horas semanales según especialidad (RF-3.3)
     */
    private function determineWeeklyHours(Professional $professional): int
    {
        return match($professional->specialty->name) {
            'Educador Diferencial' => rand(30, 44),  // Jornada completa PIE
            'Psicopedagogo'        => rand(22, 30),  // Media jornada
            'Fonoaudiólogo'        => rand(20, 25),  // Horas específicas
            default                => rand(20, 35)   // Otros casos
        };
    }

    /**
     * Genera disponibilidad semanal realista (RF-4.1)
     */
    private function generateAvailability(int $weeklyHours): array
    {
        $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $availability = [];
        $remainingHours = $weeklyHours;

        // Distribuir horas en días laborales
        foreach ($days as $day) {
            if ($remainingHours <= 0) break;

            $maxDayHours = min(8, $remainingHours); // Máximo 8 hrs/día
            $dayHours = rand(4, $maxDayHours); // Mínimo 4 hrs/día
            
            $availability[$day] = [
                'morning' => $this->generateTimeBlock(8, 12, $dayHours),
                'afternoon' => $this->generateTimeBlock(14, 18, $dayHours)
            ];
            
            $remainingHours -= $dayHours;
        }

        return $availability;
    }

    /**
     * Genera bloques horarios realistas
     */
    private function generateTimeBlock(int $startHour, int $endHour, int &$dayHours): array
    {
        if ($dayHours <= 0) return [];

        $blockHours = min(4, $dayHours); // Bloques de máximo 4 hrs
        $start = $startHour + rand(0, 2); // Variación de inicio
        $end = min($start + $blockHours, $endHour);

        $dayHours -= ($end - $start);

        return [
            'start' => sprintf("%02d:00", $start),
            'end' => sprintf("%02d:00", $end),
            'available' => true
        ];
    }
}

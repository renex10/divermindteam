<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si hay cursos creados
        if (Course::count() == 0) {
            $this->command->error('Primero ejecute CourseSeeder!');
            return;
        }

        $courses = Course::all();
        $schedulesCreated = 0;

        foreach ($courses as $course) {
            $schedules = $this->generateSchedule($course->shift);

            foreach ($schedules as $schedule) {
                DB::table('course_schedules')->insert([
                    'course_id' => $course->id,
                    'start_time' => $schedule['start'],
                    'end_time' => $schedule['end'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $schedulesCreated++;
            }
        }

        $this->command->info("{$schedulesCreated} bloques horarios creados para {$courses->count()} cursos.");
    }

    /**
     * Genera horario según jornada (RF-5.1)
     */
    private function generateSchedule(string $shift): array
    {
        return match($shift) {
            'morning' => [
                ['start' => '08:00:00', 'end' => '09:30:00'],
                ['start' => '09:45:00', 'end' => '11:15:00'],
                ['start' => '11:30:00', 'end' => '13:00:00']
            ],
            'afternoon' => [
                ['start' => '14:00:00', 'end' => '15:30:00'],
                ['start' => '15:45:00', 'end' => '17:15:00'],
                ['start' => '17:30:00', 'end' => '19:00:00']
            ],
            default => []
        };
    }
}
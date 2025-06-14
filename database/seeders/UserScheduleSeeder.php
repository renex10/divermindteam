<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() == 0) {
            $this->command->error('Primero debe ejecutar el UserSeeder!');
            return;
        }

        User::all()->each(function ($user) {
            $hours = $this->determineHoursByEmail($user->email);
            
            DB::table('user_schedules')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'weekly_hours' => $hours,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        });
    }

    /**
     * Asigna horas según tipo de usuario (detectado por email)
     */
    private function determineHoursByEmail(string $email): int
    {
        if (str_contains($email, 'educador')) {
            return rand(30, 44); // Educadores
        } elseif (str_contains($email, 'psico') || str_contains($email, 'fono')) {
            return rand(20, 30); // Especialistas
        }
        
        return rand(20, 44); // Default
    }
}






























//class UserScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    //public function run(): void
    //{
        // Verificar si hay usuarios
        //if (User::count() == 0) {
           // $this->command->error('Primero debe ejecutar el UserSeeder!');
            //return;
        //}

        // Obtener todos los usuarios con roles de profesional PIE
        //$professionals = User::whereHas('roles', function($query) {
            //$query->whereIn('name', ['educador_diferencial', 'psicopedagogo', 'fonoaudiologo']);
        //})->get();

        // Si no hay usuarios con roles, usar todos los usuarios
        //if ($professionals->isEmpty()) {
            //$professionals = User::all();
            //$this->command->warn('No se encontraron usuarios con roles PIE. Asignando horarios a todos los usuarios.');
        //}

        //foreach ($professionals as $user) {
            //DB::table('user_schedules')->updateOrInsert(
                //['user_id' => $user->id],
                //[
                    //'weekly_hours' => $this->getWeeklyHoursByRole($user),
                    //'created_at' => now(),
                    //'updated_at' => now(),
                //]
            //);
        //}

        //$this->command->info('Horarios asignados a '.$professionals->count().' profesionales PIE.');
    }

    /**
     * Asigna horas semanales según rol (basado en normativa PIE)
     */
    //private function getWeeklyHoursByRole(User $user): int
    //{
        // Lógica basada en requerimiento RF-3.3 y carga horaria PIE
        //if ($user->hasRole('educador_diferencial')) {
            //return rand(30, 44); // Jornada completa PIE
        //} elseif ($user->hasRole('psicopedagogo')) {
            //return rand(22, 30); // Media jornada común
        //} elseif ($user->hasRole('fonoaudiologo')) {
            //return rand(20, 25); // Especialistas externos
        //}

        //return 30; // Valor por defecto para otros roles
    //}
//}
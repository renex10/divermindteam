<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            [
                'name' => 'Educador Diferencial',
                'description' => 'Profesional especializado en educación especial y necesidades educativas especiales'
            ],
            [
                'name' => 'Psicopedagogo',
                'description' => 'Especialista en procesos de aprendizaje y dificultades educativas'
            ],
            [
                'name' => 'Fonoaudiólogo',
                'description' => 'Experto en trastornos de comunicación y lenguaje'
            ],
            [
                'name' => 'Terapeuta Ocupacional',
                'description' => 'Especialista en integración sensorial y desarrollo psicomotor'
            ],
            [
                'name' => 'Psicólogo Educacional',
                'description' => 'Profesional de salud mental especializado en contextos educativos'
            ],
            [
                'name' => 'Intérprete en Lengua de Señas',
                'description' => 'Especialista en comunicación para estudiantes con discapacidad auditiva'
            ],
            [
                'name' => 'Neurólogo Infantil',
                'description' => 'Médico especializado en trastornos neurológicos en población pediátrica'
            ]
        ];

        foreach ($specialties as $specialty) {
            DB::table('specialties')->updateOrInsert(
                ['name' => $specialty['name']],
                $specialty
            );
        }

        $this->command->info(count($specialties) . ' especialidades PIE creadas/actualizadas.');
    }
}
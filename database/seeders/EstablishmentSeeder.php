<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EstablishmentSeeder extends Seeder
{
    /**
     * Ejecuta el seeder para crear 10 establecimientos.
     */
    public function run(): void
    {
        // Paso 1: Instanciar Faker en español
        $faker = Faker::create('es_ES');

        // Paso 2: Obtener todas las comunas disponibles
        $communes = DB::table('communes')->get();

        // Paso 3: Verificar que existan comunas
        if ($communes->isEmpty()) {
            throw new \Exception('Primero debe ejecutar el CommuneSeeder');
        }

        // Paso 4: Limitar a máximo 10 comunas aleatorias (si hay más)
        $communes = $communes->shuffle()->take(10);

        // Paso 5: Crear un establecimiento por cada una de esas 10 comunas
        foreach ($communes as $commune) {
            $establishmentType = $faker->randomElement(['Escuela', 'Liceo', 'Colegio']);
            $establishmentName = $establishmentType . ' ' . $faker->randomElement([
                'Básica', 'Particular', 'Pública', 'Subvencionado', 
                'Técnico-Profesional', 'Artístico', 'Científico-Humanista'
            ]) . ' ' . $faker->unique()->words(2, true);

            DB::table('establishments')->insert([
                'name' => $establishmentName,
                'address' => $faker->streetAddress . ', ' . $commune->name,
                'commune_id' => $commune->id,
                'pie_quota_max' => $faker->numberBetween(5, 30),
                'is_active' => $faker->boolean(90), // 90% de probabilidad de que esté activo
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

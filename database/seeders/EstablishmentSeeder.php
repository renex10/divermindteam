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
        $faker = Faker::create('es_ES');
        $communes = DB::table('communes')->get();

        if ($communes->isEmpty()) {
            throw new \Exception('Primero debe ejecutar el CommuneSeeder');
        }

        $communes = $communes->shuffle()->take(10);

        foreach ($communes as $commune) {
            $establishmentType = $faker->randomElement(['Escuela', 'Liceo', 'Colegio']);
            $establishmentName = $establishmentType . ' ' . $faker->randomElement([
                'Básica', 'Particular', 'Pública', 'Subvencionado',
                'Técnico-Profesional', 'Artístico', 'Científico-Humanista'
            ]) . ' ' . $faker->unique()->words(2, true);

            // Generar un RBD único dentro de un rango razonable
            $rbd = $faker->unique()->numberBetween(10000, 99999);

            DB::table('establishments')->insert([
                'rbd' => $rbd,
                'name' => $establishmentName,
                'address' => $faker->streetAddress . ', ' . $commune->name,
                'commune_id' => $commune->id,
                'pie_quota_max' => $faker->numberBetween(5, 30),
                'is_active' => $faker->boolean(90),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
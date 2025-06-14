<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EstablishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        $communes = DB::table('communes')->get();
        
        if ($communes->isEmpty()) {
            throw new \Exception('Primero debe ejecutar el CommuneSeeder');
        }

        foreach ($communes as $commune) {
            // Crear 3 establecimientos por comuna
            for ($i = 1; $i <= 3; $i++) {
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
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

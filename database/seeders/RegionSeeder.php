<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chileId = DB::table('countries')->where('iso_code', 'CHL')->value('id');
        
        if (!$chileId) {
            throw new \Exception('Primero debe existir el país Chile en la tabla countries');
        }

        $regions = [
            ['name' => 'Arica y Parinacota', 'code' => 'CL-AP'],
            ['name' => 'Tarapacá', 'code' => 'CL-TA'],
            ['name' => 'Antofagasta', 'code' => 'CL-AN'],
            ['name' => 'Atacama', 'code' => 'CL-AT'],
            ['name' => 'Coquimbo', 'code' => 'CL-CO'],
            ['name' => 'Valparaíso', 'code' => 'CL-VS'],
            ['name' => 'Metropolitana de Santiago', 'code' => 'CL-RM'],
            ['name' => 'Libertador General Bernardo O\'Higgins', 'code' => 'CL-LI'],
            ['name' => 'Maule', 'code' => 'CL-ML'],
            ['name' => 'Ñuble', 'code' => 'CL-NB'],
            ['name' => 'Biobío', 'code' => 'CL-BI'],
            ['name' => 'La Araucanía', 'code' => 'CL-AR'],
            ['name' => 'Los Ríos', 'code' => 'CL-LR'],
            ['name' => 'Los Lagos', 'code' => 'CL-LL'],
            ['name' => 'Aysén del General Carlos Ibáñez del Campo', 'code' => 'CL-AI'],
            ['name' => 'Magallanes y de la Antártica Chilena', 'code' => 'CL-MA'],
        ];

        foreach ($regions as $region) {
            // Verificar si la región ya existe antes de insertar
            $exists = DB::table('regions')
                ->where('code', $region['code'])
                ->exists();
                
            if (!$exists) {
                DB::table('regions')->insert([
                    'name' => $region['name'],
                    'code' => $region['code'],
                    'country_id' => $chileId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
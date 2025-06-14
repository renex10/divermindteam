<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exists = DB::table('countries')
            ->where('iso_code', 'CHL')
            ->exists();
            
        if (!$exists) {
            DB::table('countries')->insert([
                'name' => 'Chile',
                'iso_code' => 'CHL',
                'phone_code' => '+56',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
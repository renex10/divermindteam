<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*    User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */

        $this->call([
            CountrySeeder::class,
            RegionSeeder::class,
            CommuneSeeder::class,
            EstablishmentSeeder::class,
            UserSeeder::class,
            UserDocumentSeeder::class, // Añadir este nuevo seeder
            UserPhoneSeeder::class,
            UserScheduleSeeder::class,
            SpecialtySeeder::class,
            ProfessionalSeeder::class,
            ProfessionalWorkHourSeeder::class,
            CourseSeeder::class,
                 StudentSeeder::class,
            CourseScheduleSeeder::class,
       

            // Otros seeders...
        ]);
    }
}

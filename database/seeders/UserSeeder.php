<?php

namespace Database\Seeders;

use App\Models\Establishment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar que existan establecimientos primero
        if (Establishment::count() == 0) {
            $this->command->error('Primero debe ejecutar el EstablishmentSeeder!');
            return;
        }

        // Usuario real
        User::firstOrCreate(
            ['email' => 'reneprh2013@gmail.com'],
            [
                'name' => 'rene',
                'last_name' => 'riquelme ',
                'password' => Hash::make('pangaleluney2013'),
                'email_verified_at' => now(),
                'establishment_id' => Establishment::inRandomOrder()->first()->id,
            ]
        );

        // Usuarios de prueba
        User::factory()
            ->count(49)
            ->create([
                'establishment_id' => Establishment::inRandomOrder()->first()->id,
            ]);
    }
}
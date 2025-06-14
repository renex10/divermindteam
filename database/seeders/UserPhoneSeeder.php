<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si hay usuarios
        if (User::count() == 0) {
            $this->command->error('Primero debe ejecutar el UserSeeder!');
            return;
        }

        // Obtener el ID de Chile
        $chileId = Country::where('iso_code', 'CHL')->value('id');
        if (!$chileId) {
            $this->command->error('No se encontró Chile en la tabla countries');
            return;
        }

        // Asignar teléfono al usuario rene
        $userRene = User::where('email', 'reneprh2013@gmail.com')->first();
        if ($userRene) {
            DB::table('user_phones')->updateOrInsert(
                ['user_id' => $userRene->id],
                [
                    'country_id' => $chileId,
                    'phone_number' => '987654321', // Número de ejemplo para rene
                    'primary' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Generar números para otros usuarios (máximo 2 por usuario)
        $users = User::all();
        foreach ($users as $user) {
            // Saltar si es el usuario rene (ya le asignamos número)
            if ($user->email === 'reneprh2013@gmail.com') {
                continue;
            }

            // Número principal (celular)
            DB::table('user_phones')->insert([
                'user_id' => $user->id,
                'country_id' => $chileId,
                'phone_number' => $this->generateMobileNumber(),
                'primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 50% de probabilidad de tener un segundo número (fijo)
            if (rand(0, 1)) {
                DB::table('user_phones')->insert([
                    'user_id' => $user->id,
                    'country_id' => $chileId,
                    'phone_number' => $this->generateLandlineNumber(),
                    'primary' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Genera un número de celular chileno válido (9XXXXXXXX)
     */
    private function generateMobileNumber(): string
    {
        $prefixes = ['9']; // Prefijos válidos para celulares en Chile
        $prefix = $prefixes[array_rand($prefixes)];
        return $prefix . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    /**
     * Genera un número fijo chileno válido (2XXXXXXXX)
     */
    private function generateLandlineNumber(): string
    {
        $areaCodes = ['2', '32', '33', '34', '35', '39', '41', '42', '43', '45', '51', '52', '53', '55', '57', '58', '61', '63', '64', '65', '67', '71', '72', '73', '75'];
        $areaCode = $areaCodes[array_rand($areaCodes)];
        $numberLength = 8 - strlen($areaCode);
        return $areaCode . str_pad(rand(0, pow(10, $numberLength) - 1), $numberLength, '0', STR_PAD_LEFT);
    }
}
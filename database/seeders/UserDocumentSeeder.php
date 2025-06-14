<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDocumentSeeder extends Seeder
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

        // RUT específico para el usuario rene
        $userRene = User::where('email', 'reneprh2013@gmail.com')->first();
        if ($userRene) {
            DB::table('user_documents')->insert([
                'user_id' => $userRene->id,
                'rut' => '12345678-9', // RUT de ejemplo para rene
                'verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Generar RUTs válidos para los demás usuarios
 
$usersWithoutRut = DB::table('users')
    ->leftJoin('user_documents', 'users.id', '=', 'user_documents.user_id')
    ->whereNull('user_documents.id')
    ->select('users.*')
    ->get();
        
        foreach ($usersWithoutRut as $user) {
            if ($user->email !== 'reneprh2013@gmail.com') {
                DB::table('user_documents')->insert([
                    'user_id' => $user->id,
                    'rut' => $this->generateValidRut(),
                    'verified' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Genera un RUT chileno válido
     */
    private function generateValidRut(): string
    {
        $number = rand(1000000, 25000000); // Número entre 1.000.000 y 25.000.000
        $dv = $this->calculateDv($number);
        
        return number_format($number, 0, '', '') . '-' . $dv;
    }

    /**
     * Calcula el dígito verificador de un RUT chileno
     */
    private function calculateDv(int $number): string
    {
        $s = 1;
        for ($m = 0; $number != 0; $number /= 10) {
            $s = ($s + $number % 10 * (9 - $m++ % 6)) % 11;
        }
        return chr($s ? $s + 47 : 75); // Retorna K si es 10
    }
}
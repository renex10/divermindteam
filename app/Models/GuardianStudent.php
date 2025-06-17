<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;



class GuardianStudent extends Model
{
    /** @use HasFactory<\Database\Factories\GuardianStudentFactory> */
    use HasFactory;

    public function run(): void
    {
        $students = Student::all();
        $users = User::whereDoesntHave('guardedStudents')->get();

        $relationships = ['padre', 'madre', 'tutor', 'abuelo', 'tío'];

        foreach ($students as $student) {
            // Asignar 1-3 apoderados por estudiante
            $guardiansCount = rand(1, 3);
            $guardians = $users->random($guardiansCount);

            foreach ($guardians as $index => $guardian) {
                $student->guardians()->attach($guardian->id, [
                    'is_primary' => $index === 0, // El primero es principal
                    'relationship' => $relationships[array_rand($relationships)],
                ]);
            }
        }
    }

//modelo relación estudiante-apoderado
     public function consent(): HasOne
    {
        return $this->hasOne(Consent::class);
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    /** @use HasFactory<\Database\Factories\SpecialtyFactory> */
    use HasFactory;

    // app/Models/Specialty.php
public function professionals()
{
    return $this->belongsToMany(User::class, 'professional_specialties')
                ->withPivot('certification_number', 'valid_until');
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    /** @use HasFactory<\Database\Factories\ProfessionalFactory> */
    use HasFactory;
    // app/Models/Professional.php
public function user()
{
    return $this->belongsTo(User::class);
}

public function specialty()
{
    return $this->belongsTo(Specialty::class);
}

// Scope para activos
public function scopeActive($query)
{
    return $query->where('active', true);
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ProfessionalWorkHour;


    /** @use HasFactory<\Database\Factories\ProfessionalFactory> */
 class Professional extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function workHours(): HasOne
    {
        return $this->hasOne(ProfessionalWorkHour::class);
    }
}


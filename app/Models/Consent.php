<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Consent extends Model
{
    /** @use HasFactory<\Database\Factories\ConsentFactory> */
    use HasFactory;


protected $fillable = [
        'guardian_student_id',
        'consent_pie',
        'data_processing',
        'signed_at',
         'establishment_id', // ← esto es clave

    ];

   public function relationship(): BelongsTo
    {
        return $this->belongsTo(GuardianStudent::class, 'guardian_student_id');
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

}

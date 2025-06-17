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
    ];

    public function guardianStudent(): BelongsTo
    {
        return $this->belongsTo(GuardianStudent::class);
    }


}

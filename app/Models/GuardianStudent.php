<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GuardianStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'guardian_id',
        'establishment_id',
        'is_primary',
        'relationship',
    ];

    /**
     * Relación con el estudiante.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relación con el apoderado (usuario).
     */
    public function guardian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    /**
     * Relación con el establecimiento.
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * Consentimiento asociado.
     */
    public function consent(): HasOne
    {
        return $this->hasOne(Consent::class);
    }
}
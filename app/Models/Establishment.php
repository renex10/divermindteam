<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Establishment extends Model
{
    /** @use HasFactory<\Database\Factories\EstablishmentFactory> */
    use HasFactory; // ¡Deja solo esta!

protected $fillable = [
    'rbd',
    'name',
    'address',
    'region_id',
    'commune_id',
    'pie_quota_max',
    'is_active',
];
    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function professionals(): HasMany
    {
        return $this->hasMany(Professional::class);
    }

    public function guardianRelationships(): HasMany
    {
        return $this->hasMany(GuardianStudent::class);
    }

    public function consents(): HasMany
    {
        return $this->hasMany(Consent::class);
    }

    // ¡Elimina la línea "use HasFactory;" que estaba aquí!

  
}
<?php
// app/Models/Professional.php - Este modelo parece faltar

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Professional extends Model
{
    protected $fillable = [
        'user_id',
        'specialty_id',
        'establishment_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Relación con el usuario (1:1)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la especialidad
     */
    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Relación con el establecimiento
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * Estudiantes asignados a este profesional
     */
    public function assignedStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'assigned_specialist_id');
    }

    /**
     * Horarios de trabajo
     */
    public function workHours(): HasOne
    {
        return $this->hasOne(ProfessionalWorkHour::class);
    }
}
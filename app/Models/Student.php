<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Specialty;

class Student extends Model
{
protected $fillable = [
    'user_id',
    'document_id',
    'course_id',
    'first_name',
    'last_name',
    'birth_date',
    'need_type',
    'diagnosis',
    'priority',
    'active',
    'establishment_id', // ← Esto es lo que faltaba
];

    
    protected $casts = [
        'birth_date' => 'date',
        'active' => 'boolean'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_students');
    }

    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'guardian_students', 'student_id', 'guardian_id');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

public function assignedSpecialist(): BelongsTo
    {
        return $this->belongsTo(Professional::class, 'assigned_specialist_id');
    }



}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'active'
    ];
    
    protected $casts = [
        'birth_date' => 'date',
        'active' => 'boolean'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function document(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class);
    }
    
    public function currentCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_students')
            ->withTimestamps();
    }

    // En app/Models/Student.php
public function getFullNameAttribute()
{
    return $this->user->name;
}

public function getFirstNameAttribute()
{
    return explode(' ', $this->user->name)[0];
}

public function getLastNameAttribute()
{
    return explode(' ', $this->user->name, 2)[1] ?? '';
}

public function guardianLinks()
{
    return $this->hasMany(GuardianStudent::class);
}

// Si quieres acceso directo al consentimiento del apoderado principal:
public function primaryGuardianConsent()
{
    return $this->hasOneThrough(Consent::class, GuardianStudent::class)
        ->where('guardian_students.is_primary', true);
}

public function documents()
{
    return $this->morphMany(Document::class, 'documentable');
}


}
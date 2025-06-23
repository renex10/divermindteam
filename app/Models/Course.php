<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(CourseSchedule::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'course_students');
    }

}

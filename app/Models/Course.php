<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;
    // app/Models/Course.php
public function schedules()
{
    return $this->hasMany(CourseSchedule::class);
}

// app/Models/CourseSchedule.php
public function course()
{
    return $this->belongsTo(Course::class);
}

// Para integración con sesiones PIE
/* public function pieSessions()
{
    return $this->hasMany(PieSession::class, 'schedule_id');
} */

    public function establishment()
{
    return $this->belongsTo(Establishment::class);
}

}

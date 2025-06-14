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
}
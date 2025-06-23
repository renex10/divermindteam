<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;




class GuardianStudent extends Model
{
     protected $fillable = [
        'student_id',
        'guardian_id',
        'establishment_id',
        'is_primary',
        'relationship',
    ];



    
 public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function consent(): HasOne
    {
        return $this->hasOne(Consent::class);
    }

}

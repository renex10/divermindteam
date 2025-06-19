<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;
    protected $fillable = [
    'documentable_id',
    'documentable_type',
    'type',
    'path',
    'format',
    'description'
];

    public function student()
{
    return $this->belongsTo(Student::class);
}

public function documentable()
{
    return $this->morphTo();
}


}

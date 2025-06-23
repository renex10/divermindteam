<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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

  public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

}

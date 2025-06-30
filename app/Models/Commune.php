<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importa BelongsTo

class Commune extends Model
{
    /** @use HasFactory<\Database\Factories\CommuneFactory> */
    use HasFactory;

     public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}

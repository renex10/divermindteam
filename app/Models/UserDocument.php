<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    /** @use HasFactory<\Database\Factories\UserDocumentFactory> */
    use HasFactory;

      protected $fillable = [
        'user_id',  // Añadir este campo
        'rut',      // Añadir este campo
        'verified', // Añadir este campo
    ];
    
    protected $table = 'user_documents';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

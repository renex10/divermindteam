<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    /** @use HasFactory<\Database\Factories\UserDocumentFactory> */
    use HasFactory;
    
    protected $table = 'user_documents';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

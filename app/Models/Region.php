<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /** @use HasFactory<\Database\Factories\RegionFactory> */
    use HasFactory;

    use HasFactory;

    protected $fillable = ['name', 'code', 'country_id'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function communes()
    {
        return $this->hasMany(Commune::class);
    }
}

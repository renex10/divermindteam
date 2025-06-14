<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalWorkHour extends Model
{
    /** @use HasFactory<\Database\Factories\ProfessionalWorkHourFactory> */
    use HasFactory;

    public function isAvailable($startTime, $endTime): bool
{
    return !$this->schedules()
        ->where(function($query) use ($startTime, $endTime) {
            $query->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime]);
        })->exists();
}
}

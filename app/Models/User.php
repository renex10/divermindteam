<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
      protected $fillable = [
        // Asegúrate que 'establishment_id' está en el array
        'name', 'email', 'password', 'establishment_id'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function document(): HasOne
    {
        return $this->hasOne(UserDocument::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(UserPhone::class);
    }

    public function schedule(): HasOne
    {
        return $this->hasOne(UserSchedule::class);
    }

    public function professionalProfile(): HasOne
    {
        return $this->hasOne(Professional::class);
    }

    public function taughtCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function guardianRelationships(): HasMany
    {
        return $this->hasMany(GuardianStudent::class, 'guardian_id');
    }
}

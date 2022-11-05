<?php

namespace App\Models;

use App\Models\Timer;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'username',
        'password',
        'job_title',
        'mobile',
        'phone',
        'street',
        'house_number',
        'city',
        'country',
        'zip_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'full_name'
    ];

    public const VALIDATION_RULES = [
        'name' => ['required', 'string', 'max:255'],
        'surname' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'username' => ['required', 'string', 'max:255', 'unique:users'],
        'password' => ['string', 'nullable', 'min:8'],
        'job_title' => ['string', 'nullable', 'max:255'],
        'mobile' => ['string', 'nullable', 'max:255'],
        'phone' => ['string', 'nullable', 'max:255'],
        'street' => ['string', 'nullable', 'max:255'],
        'house_number' => ['string', 'nullable', 'max:255'],
        'city' => ['string', 'nullable', 'max:255'],
        'country' => ['string', 'nullable', 'max:255'],
        'zip_code' => ['string', 'nullable', 'max:255'],
    ];

    public function timers(): HasMany
    {
        return $this->hasMany(Timer::class, 'user_id');
    }

    public function activeTimers(): HasMany
    {
        return $this->hasMany(Timer::class, 'user_id')->active(true);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class, 'user_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->name . ' ' . $this->surname;
    }
}

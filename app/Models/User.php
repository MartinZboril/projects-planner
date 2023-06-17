<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [
        'id', 'email_verified_at', 'remember_token', 'created_at', 'updated_at',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'full_name',
        'job_title_label',
        'mobile_label',
    ];

    public const VALIDATION_RULES = [
        'name' => ['required', 'string', 'max:255'],
        'surname' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'username' => ['required', 'string', 'max:255', 'unique:users'],
        'password' => ['string', 'nullable', 'min:8'],
        'avatar' => ['nullable', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        'job_title' => ['string', 'nullable', 'max:255'],
        'mobile' => ['string', 'nullable', 'max:255'],
        'phone' => ['string', 'nullable', 'max:255'],
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id');
    }

    public function rates(): BelongsToMany
    {
        return $this->belongsToMany(Rate::class, 'user_rate', 'user_id', 'rate_id');
    }

    public function timers(): HasMany
    {
        return $this->hasMany(Timer::class, 'user_id');
    }

    public function activeTimers(): HasMany
    {
        return $this->hasMany(Timer::class, 'user_id')->active(true);
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => Hash::make($value),
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name.' '.$this->surname,
        );
    }

    protected function jobTitleLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->job_title ?? 'NaN',
        );
    }

    protected function mobileLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->mobile ?? 'NaN',
        );
    }

    protected function phoneLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->phone ?? 'NaN',
        );
    }
}

<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

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
        'phone_label',
        'street_label',
        'city_label',
        'zip_code_label',
        'country_label',
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
        'street' => ['string', 'nullable', 'max:255'],
        'house_number' => ['string', 'nullable', 'max:255'],
        'city' => ['string', 'nullable', 'max:255'],
        'country' => ['string', 'nullable', 'max:255'],
        'zip_code' => ['string', 'nullable', 'max:255'],
    ];

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(File::class, 'avatar_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id');
    }

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

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => Hash::make($value),
        );
    }

    public function getFullNameAttribute(): string
    {
        return $this->name . ' ' . $this->surname;
    }
    
    public function getJobTitleLabelAttribute(): string
    {
        return $this->job_title ?? 'NaN';
    }

    public function getMobileLabelAttribute(): string
    {
        return $this->mobile ?? 'NaN';
    }

    public function getPhoneLabelAttribute(): string
    {
        return $this->phone ?? 'NaN';
    }

    public function getStreetLabelAttribute(): string
    {
        return ($this->street ?? 'NaN') . ($this->house_number ? ' ' . $this->house_number : '');
    }
    
    public function getCityLabelAttribute(): string
    {
        return $this->city ?? 'NaN';
    }
        
    public function getZipCodeLabelAttribute(): string
    {
        return $this->zip_code ?? 'NaN';
    }

    public function getCountryLabelAttribute(): string
    {
        return $this->country ?? 'NaN';
    }
}

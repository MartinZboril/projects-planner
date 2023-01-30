<?php

namespace App\Models;

use App\Models\Timer;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
    
    public function getJobTitleLabelAttribute(): string
    {
        return ($this->job_title) ? $this->job_title : 'NaN';
    }

    public function getMobileLabelAttribute(): string
    {
        return ($this->mobile) ? $this->mobile : 'NaN';
    }

    public function getPhoneLabelAttribute(): string
    {
        return ($this->phone) ? $this->phone : 'NaN';
    }

    public function getStreetLabelAttribute(): string
    {
        return (($this->street) ? $this->street : 'NaN') . (($this->house_number) ? ' ' . $this->house_number : '');
    }
    
    public function getCityLabelAttribute(): string
    {
        return ($this->city) ? $this->city : 'NaN';
    }
        
    public function getZipCodeLabelAttribute(): string
    {
        return ($this->zip_code) ? $this->zip_code : 'NaN';
    }

    public function getCountryLabelAttribute(): string
    {
        return ($this->country) ? $this->country : 'NaN';
    }
}

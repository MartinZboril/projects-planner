<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street', 'house_number', 'city', 'country', 'zip_code',
    ];

    public const VALIDATION_RULES = [
        'street' => ['nullable', 'string', 'max:255'],
        'house_number' => ['nullable', 'string', 'max:255'],
        'city' => ['nullable', 'string', 'max:255'],
        'country' => ['nullable', 'string', 'max:255'],
        'zip_code' => ['nullable', 'string', 'max:255'],
    ];

    protected function streetLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->street ?? 'NaN',
        );
    }

    protected function houseNumberLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->house_number ?? 'NaN',
        );
    }
    
    protected function cityLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->city ?? 'NaN',
        );
    }

    protected function countryLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->country ?? 'NaN',
        );
    }

    protected function zipCodeLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->zip_code ?? 'NaN',
        );
    }
}

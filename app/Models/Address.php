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

    protected function street(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ?? 'NaN',
        );
    }

    protected function houseNumber(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ?? 'NaN',
        );
    }
    
    protected function city(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ?? 'NaN',
        );
    }

    protected function country(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ?? 'NaN',
        );
    }

    protected function zipCode(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ?? 'NaN',
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'file_path',
    ];

    public const VALIDATION_RULES = [
        'name' => ['required', 'max:255'],
        'file_path' => ['required', 'max:255'],
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function kilobytesSize(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => round($attributes['size'] / 1000, 2),
        );
    }
}

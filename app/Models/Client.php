<?php

namespace App\Models;

use App\Traits\Scopes\MarkedRecords;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Client extends Model
{
    use HasFactory, MarkedRecords;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    public const VALIDATION_RULES = [
        'name' => ['required', 'string', 'max:255', 'unique:clients'],
        'email' => ['required', 'string', 'email', 'max:255'],
        'contact_person' => ['nullable', 'string', 'max:255'],
        'contact_email' => ['nullable', 'string', 'max:255'],
        'mobile' => ['nullable', 'string', 'max:255'],
        'phone' => ['nullable', 'string', 'max:255'],
        'note' => ['nullable', 'string', 'max:65553'],
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'logo_id');
    }

    public function socialNetwork(): BelongsTo
    {
        return $this->belongsTo(SocialNetwork::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    protected function contactPersonLabel(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['contact_person'] ?? 'NaN',
        );
    }

    protected function contactEmailLabel(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['contact_email'] ?? 'NaN',
        );
    }

    protected function mobileLabel(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['mobile'] ?? 'NaN',
        );
    }

    protected function phoneLabel(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['phone'] ?? 'NaN',
        );
    }
}

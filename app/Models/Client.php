<?php

namespace App\Models;

use App\Traits\Scopes\MarkedRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Client extends Model
{
    use HasFactory, MarkedRecords;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected $appends = [
        'email_label',
        'contact_person_label',
        'contact_email_label',
        'mobile_label',
        'phone_label',
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

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function getEmailLabelAttribute(): string
    {
        return $this->email ?? 'NaN';
    }

    public function getContactPersonLabelAttribute(): string
    {
        return $this->contact_person ?? 'NaN';
    }

    public function getContactEmailLabelAttribute(): string
    {
        return $this->contact_email ?? 'NaN';
    }

    public function getMobileLabelAttribute(): string
    {
        return $this->mobile ?? 'NaN';
    }

    public function getPhoneLabelAttribute(): string
    {
        return $this->phone ?? 'NaN';
    }
}

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
        'street_label',
        'city_label',
        'zip_code_label',
        'country_label',
    ];

    public const VALIDATION_RULES = [
        'name' => ['required', 'string', 'max:255', 'unique:clients'],
        'email' => ['required', 'string', 'email', 'max:255'],
        'contact_person' => ['nullable', 'string', 'max:255'],
        'contact_email' => ['nullable', 'string', 'max:255'],
        'mobile' => ['nullable', 'string', 'max:255'],
        'phone' => ['nullable', 'string', 'max:255'],
        'street' => ['nullable', 'string', 'max:255'],
        'house_number' => ['nullable', 'string', 'max:255'],
        'city' => ['nullable', 'string', 'max:255'],
        'country' => ['nullable', 'string', 'max:255'],
        'zip_code' => ['nullable', 'string', 'max:255'],
        'website' => ['nullable', 'string'],
        'skype' => ['nullable', 'string'],
        'linekedin' => ['nullable', 'string'],
        'twitter' => ['nullable', 'string'],
        'facebook' => ['nullable', 'string'],
        'instagram' => ['nullable', 'string'],
        'note' => ['nullable', 'string', 'max:65553'],
    ];
    
    public function logo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'logo_id');
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class, 'clients_notes', 'client_id', 'note_id')->visible()->orderByDesc('is_marked');
    }

    public function comments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'clients_comments', 'client_id', 'comment_id')->orderByDesc('created_at');
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

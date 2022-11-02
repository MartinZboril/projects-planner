<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

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
    
    public function getEmailLabelAttribute(): string
    {
        return ($this->email) ? $this->email : 'NaN';
    }

    public function getContactPersonLabelAttribute(): string
    {
        return ($this->contact_person) ? $this->contact_person : 'NaN';
    }

    public function getContactEmailLabelAttribute(): string
    {
        return ($this->contact_email) ? $this->contact_email : 'NaN';
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

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
}

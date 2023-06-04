<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialNetwork extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'website', 'skype', 'linkedin', 'twitter', 'facebook', 'instagram',
    ];

    public const VALIDATION_RULES = [
        'website' => ['nullable', 'string'],
        'skype' => ['nullable', 'string'],
        'linkedin' => ['nullable', 'string'],
        'twitter' => ['nullable', 'string'],
        'facebook' => ['nullable', 'string'],
        'instagram' => ['nullable', 'string'],
    ];
}

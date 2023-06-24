<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = ['name'];

    public const VALIDATION_RULES = [
        'name' => ['required', 'max:255'],
        'ident' => ['required', 'max:255', 'unique:permissions'],
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function sluggable(): array
    {
        return [
            'ident' => [
                'source' => 'name',
            ],
        ];
    }
}

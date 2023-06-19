<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'is_active', 'is_primary', 'note',
    ];

    public const VALIDATION_RULES = [
        'name' => ['required', 'max:255'],
        'is_active' => ['boolean'],
        'is_primary' => ['boolean'],
        'note' => ['max:65553'],
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}

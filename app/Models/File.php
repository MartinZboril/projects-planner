<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path'
    ];

    public const VALIDATION_RULES = [
        'name' => ['required', 'max:255'],
        'file_path' => ['required', 'max:255'],
    ];
}

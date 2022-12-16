<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Support\Facades\Auth;

class Note extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

    public const VALIDATION_RULES = [
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'name' => ['required', 'max:255'],
        'content' => ['required', 'max:65553'],
        'is_private' => ['boolean'],
        'is_basic' => ['boolean']
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_private', false)
                        ->orWhere('is_private', true)->where('user_id', Auth::id());
    }
    
    public function scopeBasic(Builder $query): Builder
    {
        return $query->where('is_basic', true);
    }
}

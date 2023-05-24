<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'noteable_id', 'noteable_type', 'user_id', 'name', 'content', 'is_private', 'is_basic', 'is_marked',
    ];

    public const VALIDATION_RULES = [
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'name' => ['required', 'max:255'],
        'content' => ['required', 'max:65553'],
        'is_private' => ['boolean'],
        'is_basic' => ['boolean'],
        'is_marked' => ['boolean']
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fileable()
    {
        return $this->morphTo();
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_private', false)->orWhere('is_private', true)->where('user_id', Auth::id());
    }
    
    public function scopeBasic(Builder $query): Builder
    {
        return $query->where('is_basic', true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'noteable_id', 'noteable_type', 'user_id', 'name', 'content', 'is_private', 'is_marked',
    ];

    public const VALIDATION_RULES = [
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'name' => ['required', 'max:255'],
        'content' => ['required', 'max:65553'],
        'is_private' => ['boolean'],
        'is_marked' => ['boolean'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_private', false)->orWhere('is_private', true)->where('user_id', Auth::id());
    }

    public function scopeBasic(Builder $query): Builder
    {
        return $query->where('noteable_id', null)->where('noteable_type', null);
    }
}

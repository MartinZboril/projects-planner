<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, BelongsTo, MorphMany, MorphTo};

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'commentable_id', 'commentable_type', 'user_id', 'content',
    ]; 

    public const VALIDATION_RULES = [
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'content' => ['required', 'max:65553'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}

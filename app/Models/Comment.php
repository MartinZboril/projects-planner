<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, BelongsTo};

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'content',
    ]; 

    public const VALIDATION_RULES = [
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'content' => ['required', 'max:65553'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'comments_files', 'comment_id', 'file_id');
    }
}

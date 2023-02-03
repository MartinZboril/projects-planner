<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentFile extends Model
{
    use HasFactory;

    protected $table = 'comments_files';

    protected $fillable = [
        'comment_id', 'file_id',
    ];

    public const VALIDATION_RULES = [
        'comment_id' => ['required', 'integer', 'exists:comments,id'],
        'file_id' => ['required', 'integer', 'exists:files,id'],
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}

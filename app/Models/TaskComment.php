<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskComment extends Model
{
    use HasFactory;
        
    protected $table = 'tasks_comments';

    protected $fillable = [
        'task_id',
        'comment_id',
    ];

    public const VALIDATION_RULES = [
        'task_id' => ['required', 'integer', 'exists:tasks,id'],
        'comment_id' => ['required', 'integer', 'exists:comments,id'],
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}

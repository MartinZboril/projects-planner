<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToDo extends Model
{
    use HasFactory;

    protected $table = 'todos';
    
    protected $dates = ['deadline'];

    public const VALIDATION_RULES = [
        'task_id' => ['required', 'integer', 'exists:tasks,id'],
        'name' => ['required', 'string', 'max:255'],
        'deadline' => ['required', 'date'],
        'is_finished' => ['boolean'],
        'description' => ['max:65553'],
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}

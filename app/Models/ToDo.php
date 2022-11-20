<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToDo extends Model
{
    use HasFactory;

    protected $table = 'todos';
    
    protected $guarded = ['id']; 

    protected $dates = ['deadline'];

    protected $appends = [
        'overdue',
    ];

    public const VALIDATION_RULES = [
        'task_id' => ['required', 'integer', 'exists:tasks,id'],
        'name' => ['required', 'string', 'max:255'],
        'deadline' => ['required', 'date'],
        'is_finished' => ['boolean'],
        'description' => ['max:65553'],
    ];

    public const FINISH = 'finish';
    public const RETURN = 'return';

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function scopeFinished(Builder $query, bool $type): Builder
    {
        return $query->where('is_finished', $type);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereDate('deadline', '<=', date('Y-m-d'));
    }

    public function getOverdueAttribute(): bool
    {
        return $this->deadline <= date('Y-m-d') && !$this->is_finished;
    }
}

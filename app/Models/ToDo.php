<?php

namespace App\Models;

use App\Traits\Scopes\OverdueRecords;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToDo extends Model
{
    
    use HasFactory, OverdueRecords;

    protected $table = 'todos';
    
    protected $fillable = [
        'task_id', 'name', 'due_date', 'is_finished', 'description', 'is_marked',
    ]; 

    protected $dates = ['due_date'];

    protected $appends = [
        'overdue',
    ];

    public const VALIDATION_RULES = [
        'task_id' => ['required', 'integer', 'exists:tasks,id'],
        'name' => ['required', 'string', 'max:255'],
        'due_date' => ['required', 'date'],
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

    public function getOverdueAttribute(): bool
    {
        return $this->due_date <= date('Y-m-d') && !$this->is_finished;
    }

}

<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

class Milestone extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

    protected $dates = ['start_date', 'end_date'];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'owner_id' => ['required', 'integer', 'exists:users,id'],
        'name' => ['required', 'string', 'max:255'],
        'start_date' => ['required', 'date'],
        'end_date' => ['required', 'date', 'after:start_date'],
        'colour' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:65553'],
    ];

    protected $appends = [
        'overdue',
        'progress',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'milestones_files', 'milestone_id', 'file_id')->orderByDesc('created_at');
    }
    
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'milestone_id');
    }

    public function tasksCompleted(): HasMany
    {
        return $this->hasMany(Task::class, 'milestone_id')->status(TaskStatusEnum::complete);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereDate('end_date', '<=', date('Y-m-d'));
    }

    public function scopeMarked(Builder $query): Builder
    {
        return $query->where('is_marked', true);
    }

    public function getOverdueAttribute(): bool
    {
        return $this->end_date <= date('Y-m-d') && $this->progress < 1;
    }

    public function getProgressAttribute(): float
    {
        return ($this->tasks->count() > 0) ? round($this->tasksCompleted->count() / $this->tasks->count(), 2) : 0;
    }
}

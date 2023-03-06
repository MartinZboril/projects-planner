<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use App\Traits\Scopes\{MarkedRecords, OverdueRecords};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

class Milestone extends Model
{
    use HasFactory, MarkedRecords, OverdueRecords;

    protected $fillable = [
        'project_id', 'owner_id', 'name', 'start_date', 'due_date', 'colour', 'description', 'is_marked',
    ]; 

    protected $dates = ['start_date', 'due_date'];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'owner_id' => ['required', 'integer', 'exists:users,id'],
        'name' => ['required', 'string', 'max:255'],
        'start_date' => ['required', 'date'],
        'due_date' => ['required', 'date', 'after:start_date'],
        'colour' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:65553'],
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
    
    public function comments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'milestones_comments', 'milestone_id', 'comment_id')->orderByDesc('created_at');
    }
    
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'milestone_id');
    }

    public function tasksCompleted(): HasMany
    {
        return $this->tasks()->status(TaskStatusEnum::complete);
    }

    public function getOverdueAttribute(): bool
    {
        return $this->due_date <= date('Y-m-d') && $this->progress < 1;
    }

    public function getProgressAttribute(): float
    {
        return ($this->tasks->count() > 0) ? round($this->tasksCompleted->count() / $this->tasks->count(), 2) : 0;
    }
}

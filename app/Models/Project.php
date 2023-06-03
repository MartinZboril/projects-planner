<?php

namespace App\Models;

use App\Traits\Scopes\{MarkedRecords, OverdueRecords};
use App\Enums\{ProjectStatusEnum, TaskStatusEnum};
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

class Project extends Model
{
    use HasFactory, MarkedRecords, OverdueRecords;

    protected $fillable = [
        'status', 'client_id', 'name', 'start_at', 'due_at', 'estimated_hours', 'budget', 'description', 'is_marked',
    ]; 

    public const VALIDATION_RULES = [
        'client_id' => ['required', 'integer', 'exists:clients,id'],
        'name' => ['required', 'string', 'max:255'],
        'team' => ['required', 'array'],
        'start_at' => ['required', 'date'],
        'due_at' => ['required', 'date'],
        'estimated_hours' => ['required', 'date'],
        'estimated_hours' => ['required', 'integer', 'min:0'],
        'budget' => ['required', 'integer', 'min:0'],
        'description' => ['required', 'string', 'max:65553'],
        'status' => ['required', 'integer'],
    ];

    protected $appends = [
        'overdue',
        'deadline',
        'remaining_hours',
        'total_time',
        'time_plan',
        'amount',
        'remaining_budget',
        'budget_plan',
        'pending_tasks_count',
        'done_tasks_count',
        'tasks_plan',
    ];

    protected $casts = [
        'status' => ProjectStatusEnum::class,
        'start_at' => 'date',
        'due_at' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    public function newTasks(): HasMany
    {
        return $this->tasks()->status(TaskStatusEnum::new);
    }

    public function inProgressTasks(): HasMany
    {
        return $this->tasks()->status(TaskStatusEnum::in_progress);
    }

    public function completedTasks(): HasMany
    {
        return $this->tasks()->status(TaskStatusEnum::complete);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class, 'project_id');
    }

    public function timers(): HasMany
    {
        return $this->hasMany(Timer::class, 'project_id');
    }
    
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'project_id');
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function scopeStatus(Builder $query, ProjectStatusEnum $type): Builder
    {
        return $query->where('status', $type);
    }

    public function scopeDone(Builder $query): Builder
    {
        return $query->whereIn('status', [ProjectStatusEnum::finish, ProjectStatusEnum::archive]);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ProjectStatusEnum::active);
    }

    public function getOverdueAttribute(): bool
    {
        return $this->due_at <= date('Y-m-d') && $this->status === ProjectStatusEnum::active;
    }

    public function getDeadlineAttribute(): int
    {
        return $this->status === ProjectStatusEnum::finish ? 0 : (($this->overdue ? -1 : 1) * abs($this->due_at->diffInDays(now()->format('Y-m-d'))));
    }
       
    public function getTotalTimeAttribute(): float
    {
        return round(Timer::where('project_id', $this->id)->get()->sum('total_time'), 2);
    }

    public function getRemainingHoursAttribute(): float
    {
        return round($this->estimated_hours - $this->total_time, 2);
    }
    
    public function getTimePlanAttribute(): float
    {
        return ($this->estimated_hours) ? round(($this->total_time / $this->estimated_hours), 2) * 100 : 0;
    }

    public function getRemainingBudgetAttribute(): float
    {
        return round($this->budget - $this->amount, 2);
    }

    public function getAmountAttribute(): float
    {
        return round(Timer::where('project_id', $this->id)->get()->sum('amount'), 2);
    }
     
    public function getBudgetPlanAttribute(): float
    {
        return ($this->budget) ? round(($this->amount / $this->budget), 2) * 100 : 0;
    }

    public function getPendingTasksCountAttribute(): int
    {
        return $this->newTasks()->count() + $this->inProgressTasks()->count();
    }

    public function getDoneTasksCountAttribute(): int
    {
        return $this->completedTasks()->count();
    }

    public function getTasksPlanAttribute(): float
    {
        return ($this->tasks()->count()) ? round(($this->done_tasks_count / $this->tasks()->count()), 2) * 100 : 0;
    }
}

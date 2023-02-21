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
        'status', 'client_id', 'name', 'start_date', 'due_date', 'estimated_hours', 'budget', 'description', 'is_marked',
    ]; 

    protected $dates = ['start_date', 'due_date'];

    public const VALIDATION_RULES = [
        'client_id' => ['required', 'integer', 'exists:clients,id'],
        'name' => ['required', 'string', 'max:255'],
        'team' => ['required', 'array'],
        'start_date' => ['required', 'date'],
        'due_date' => ['required', 'date'],
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
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'projects_users', 'project_id', 'user_id');
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'projects_files', 'project_id', 'file_id')->orderByDesc('created_at');
    }

    public function comments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'projects_comments', 'project_id', 'comment_id')->orderByDesc('created_at');
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

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class, 'projects_notes', 'project_id', 'note_id')->visible()->orderByDesc('is_marked');
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
        return $this->due_date <= date('Y-m-d') && $this->status === ProjectStatusEnum::active;
    }

    public function getDeadlineAttribute(): int
    {
        return ($this->overdue ? -1 : 1) * abs($this->due_date->diffInDays(now()->format('Y-m-d')));
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

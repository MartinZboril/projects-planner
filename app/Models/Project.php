<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\{ProjectStatusEnum, TaskStatusEnum};
use Illuminate\Database\Eloquent\{Builder, Model};
use App\Traits\Scopes\{MarkedRecords, OverdueRecords};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

class Project extends Model
{
    use HasFactory, MarkedRecords, OverdueRecords;

    protected $fillable = [
        'status', 'client_id', 'name', 'started_at', 'dued_at', 'estimated_hours', 'budget', 'description', 'is_marked',
    ]; 

    public const VALIDATION_RULES = [
        'client_id' => ['required', 'integer', 'exists:clients,id'],
        'name' => ['required', 'string', 'max:255'],
        'team' => ['required', 'array'],
        'started_at' => ['required', 'date'],
        'dued_at' => ['required', 'date'],
        'estimated_hours' => ['required', 'date'],
        'estimated_hours' => ['required', 'integer', 'min:0'],
        'budget' => ['required', 'integer', 'min:0'],
        'description' => ['required', 'string', 'max:65553'],
        'status' => ['required', 'integer'],
    ];

    protected $casts = [
        'status' => ProjectStatusEnum::class,
        'started_at' => 'date',
        'dued_at' => 'date',
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

    protected function deadlineOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->dued_at <= date('Y-m-d') && $this->status === ProjectStatusEnum::active,
        );
    }

    protected function budgetOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->budget_plan > 100,
        );
    }

    protected function timePlanOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->time_plan > 100,
        );
    }

    protected function deadline(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === ProjectStatusEnum::finish ? 0 : (($this->deadline_overdue ? -1 : 1) * abs($this->dued_at->diffInDays(now()->format('Y-m-d')))),
        );
    }

    protected function totalTime(): Attribute
    {
        return Attribute::make(
            get: fn () => round($this->timers->sum('total_time'), 2),
        );
    }

    protected function remainingHours(): Attribute
    {
        return Attribute::make(
            get: fn () => round($this->estimated_hours - $this->total_time, 2),
        );
    }

    protected function timePlan(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->estimated_hours) ? round(($this->total_time / $this->estimated_hours), 2) * 100 : 0,
        );
    }

    protected function remainingBudget(): Attribute
    {
        return Attribute::make(
            get: fn () => round($this->budget - $this->amount, 2),
        );
    }
    
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn () => round($this->timers->sum('amount'), 2),
        );
    }

    protected function budgetPlan(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->budget) ? round(($this->amount / $this->budget), 2) * 100 : 0,
        );
    }

    protected function pendingTasksCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->newTasks()->count() + $this->inProgressTasks()->count(),
        );
    }

    protected function doneTasksCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->completedTasks()->count(),
        );
    }

    protected function tasksPlan(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->tasks()->count()) ? round(($this->done_tasks_count / $this->tasks()->count()), 2) * 100 : 0,
        );
    }
}

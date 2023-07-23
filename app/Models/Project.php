<?php

namespace App\Models;

use App\Enums\ProjectStatusEnum;
use App\Enums\TaskStatusEnum;
use App\Events\Project\ProjectDeleted;
use App\Traits\Scopes\MarkedRecords;
use App\Traits\Scopes\OverdueRecords;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use HasFactory, MarkedRecords, OverdueRecords, SoftDeletes, CascadeSoftDeletes, LogsActivity;

    protected $fillable = [
        'status', 'client_id', 'name', 'started_at', 'dued_at', 'estimated_hours', 'budget', 'description', 'is_marked',
    ];

    protected $cascadeDeletes = ['tasks', 'milestones', 'tickets', 'timers'];

    protected $dispatchesEvents = [
        'deleted' => ProjectDeleted::class,
    ];

    public const VALIDATION_RULES = [
        'client_id' => ['required', 'integer', 'exists:clients,id'],
        'name' => ['required', 'string', 'max:255'],
        'team' => ['required', 'array'],
        'started_at' => ['required', 'date'],
        'dued_at' => ['required', 'date'],
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'team', 'started_at', 'dued_at', 'description'])
            ->dontLogIfAttributesChangedOnly(['team', 'status', 'is_marked', 'updated_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Project was {$eventName}.");
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
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

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable')->orderByDesc('created_at');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->orderByDesc('created_at');
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable')->orderByDesc('created_at');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')->orderByDesc('created_at');
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
            get: fn ($value, $attributes) => $attributes['dued_at'] <= date('Y-m-d') && $this->status === ProjectStatusEnum::active,
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
            get: fn ($value, $attributes) => round($attributes['estimated_hours'] - $this->total_time, 2),
        );
    }

    protected function timePlan(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => ($attributes['estimated_hours']) ? round(($this->total_time / $attributes['estimated_hours']), 2) * 100 : 0,
        );
    }

    protected function remainingBudget(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => round($attributes['budget'] - $this->amount, 2),
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
            get: fn ($value, $attributes) => ($attributes['budget']) ? round(($this->amount / $attributes['budget']), 2) * 100 : 0,
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

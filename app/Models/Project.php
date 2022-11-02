<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

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
    ];

    protected $appends = [
        'deadline',
        'remaining_hours',
        'used_budget',
    ];

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
        return $this->hasMany(Task::class, 'project_id')->status(1);
    }

    public function inProgressTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id')->status(2);
    }

    public function completedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id')->status(3);
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

    public function getDeadlineAttribute(): int
    {
        return $this->due_date->diffInDays(now()->format('Y-m-d'));
    }

    public function getRemainingHoursAttribute(): int
    {
        // Todo
        return $this->estimated_hours;
    }

    public function getUsedBudgetAttribute(): int
    {
        // Todo
        return $this->budget;
    }
}

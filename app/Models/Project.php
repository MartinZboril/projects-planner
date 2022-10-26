<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Project extends Model
{
    use HasFactory;

    protected $dates = ['start_date', 'due_date'];

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
        return $this->hasMany(Task::class, 'project_id')->new();
    }

    public function inProgressTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id')->inProgress();
    }

    public function completedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id')->completed();
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
        return $this->due_date->diffInDays(\Carbon\Carbon::now()->format('Y-m-d'));
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

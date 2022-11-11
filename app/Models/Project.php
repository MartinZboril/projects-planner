<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};
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
        'status' => ['required', 'integer', 'in:1,2,3'],
    ];

    protected $appends = [
        'deadline',
        'remaining_hours',
        'total_time',
        'time_plan',
        'amount',
        'remaining_budget',
        'budget_plan',
    ];

    public const STATUSES = [
        1 => 'active',
        2 => 'finish',
        3 => 'archive',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'projects_users', 'project_id', 'user_id');
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

    public function scopeStatus(Builder $query, int $type): Builder
    {
        return $query->where('status', $type);
    }

    public function getDeadlineAttribute(): int
    {
        return $this->due_date->diffInDays(now()->format('Y-m-d'));
    }
       
    public function getTotalTimeAttribute(): int
    {
        return Timer::where('project_id', $this->id)->get()->sum('total_time');
    }

    public function getRemainingHoursAttribute(): int
    {
        return $this->estimated_hours - $this->total_time;
    }
    
    public function getTimePlanAttribute(): int
    {
        return ($this->estimated_hours) ? ($this->total_time / $this->estimated_hours) * 100 : 0;
    }

    public function getRemainingBudgetAttribute(): int
    {
        return $this->budget - $this->amount;
    }

    public function getAmountAttribute(): int
    {
        return Timer::where('project_id', $this->id)->get()->sum('amount');
    }
     
    public function getBudgetPlanAttribute(): int
    {
        return ($this->budget) ? ($this->amount / $this->budget) * 100 : 0;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

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

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'milestone_id');
    }

    public function tasksCompleted(): HasMany
    {
        return $this->hasMany(Task::class, 'milestone_id')->status(3);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereDate('end_date', '<=', date('Y-m-d'));
    }

    public function getProgressAttribute(): float
    {
        return ($this->tasks->count() > 0) ? round($this->tasksCompleted->count() / $this->tasks->count(), 2) : 0;
    }
}

<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\Scopes\{MarkedRecords, OverdueRecords};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

class Milestone extends Model
{
    use HasFactory, MarkedRecords, OverdueRecords;

    protected $fillable = [
        'project_id', 'owner_id', 'name', 'started_at', 'dued_at', 'colour', 'description', 'is_marked',
    ]; 

    protected $casts = [
        'started_at' => 'date',
        'dued_at' => 'date',
    ];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'owner_id' => ['required', 'integer', 'exists:users,id'],
        'name' => ['required', 'string', 'max:255'],
        'started_at' => ['required', 'date'],
        'dued_at' => ['required', 'date', 'after:started_at'],
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
        return $this->hasMany(Task::class, 'milestone_id');
    }

    public function tasksCompleted(): HasMany
    {
        return $this->tasks()->status(TaskStatusEnum::complete);
    }

    protected function overdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->dued_at <= date('Y-m-d') && $this->progress < 1,
        );
    }

    protected function progress(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->tasks->count() > 0) ? round($this->tasksCompleted->count() / $this->tasks->count(), 2) : 0,
        );
    }
}

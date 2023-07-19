<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Spatie\Activitylog\LogOptions;
use App\Traits\Scopes\MarkedRecords;
use App\Traits\Scopes\OverdueRecords;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use App\Events\Milestone\MilestoneCreated;
use App\Events\Milestone\MilestoneDeleted;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Milestone extends Model
{
    use HasFactory, MarkedRecords, OverdueRecords, SoftDeletes, LogsActivity;

    protected $fillable = [
        'project_id', 'owner_id', 'name', 'started_at', 'dued_at', 'colour', 'description', 'is_marked',
    ];

    protected $dispatchesEvents = [
        'created' => MilestoneCreated::class,
        'deleted' => MilestoneDeleted::class,
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'project', 'owner', 'started_at', 'dued_at', 'colour', 'description'])
            ->dontLogIfAttributesChangedOnly(['owner_id', 'is_marked', 'updated_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Milestone was {$eventName}.");
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id')->withTrashed();
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable')->orderByDesc('created_at');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->orderByDesc('created_at');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')->orderByDesc('created_at');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'milestone_id');
    }

    public function tasksCompleted(): HasMany
    {
        return $this->tasks()->status(TaskStatusEnum::complete);
    }

    protected function deadlineOverdue(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['dued_at'] <= date('Y-m-d') && $this->progress < 1,
        );
    }

    protected function progress(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->tasks->count() > 0) ? round($this->tasksCompleted->count() / $this->tasks->count(), 2) : 0,
        );
    }
}

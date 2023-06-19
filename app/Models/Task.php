<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use App\Traits\Scopes\MarkedRecords;
use App\Traits\Scopes\OverdueRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Task extends Model
{
    use HasFactory, MarkedRecords, OverdueRecords;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'status' => TaskStatusEnum::class,
        'started_at' => 'date',
        'dued_at' => 'date',
    ];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'author_id' => ['required', 'integer', 'exists:users,id'],
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'milestone_id' => ['nullable', 'integer', 'exists:milestones,id'],
        'ticket_id' => ['nullable', 'integer', 'exists:tickets,id'],
        'status' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:255'],
        'started_at' => ['required', 'date'],
        'dued_at' => ['required', 'date'],
        'description' => ['required', 'string', 'max:65553'],
    ];

    protected $appends = [
        'paused',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function todos(): HasMany
    {
        return $this->hasMany(ToDo::class, 'task_id');
    }

    public function scopeStatus(Builder $query, TaskStatusEnum $type): Builder
    {
        return $query->where('status', $type);
    }

    public function scopeStopped(Builder $query, bool $type): Builder
    {
        return $query->where('is_stopped', $type);
    }

    public function scopeDone(Builder $query): Builder
    {
        return $query->where('status', TaskStatusEnum::complete);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [TaskStatusEnum::new, TaskStatusEnum::in_progress]);
    }

    protected function deadlineOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->dued_at <= date('Y-m-d') && $this->status != TaskStatusEnum::complete,
        );
    }

    protected function milestoneLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->milestone->name ?? 'NaN',
        );
    }

    protected function paused(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['is_stopped'],
        );
    }

    protected function returned(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['is_returned'],
        );
    }
}

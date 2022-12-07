<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

    protected $dates = ['start_date', 'due_date'];

    protected $appends = [
        'milestone_label',
        'overdue',
        'paused',
        'returned',
    ];

    protected $casts = [
        'status' => TaskStatusEnum::class,
    ];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'author_id' => ['required', 'integer', 'exists:users,id'],
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'milestone_id' => ['nullable', 'integer', 'exists:milestones,id'],
        'status' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:255'],
        'start_date' => ['required', 'date'],
        'due_date' => ['required', 'date'],
        'description' => ['required', 'string', 'max:65553'],
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

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereDate('due_date', '<=', date('Y-m-d'));
    }

    public function getOverdueAttribute(): bool
    {
        return $this->due_date <= date('Y-m-d') && $this->status != TaskStatusEnum::complete;
    }

    public function getMilestoneLabelAttribute(): string
    {
        return $this->milestone ? $this->milestone->name : 'NaN';
    }

    public function getPausedAttribute(): bool
    {
        return $this->is_stopped ? true : false;
    }

    public function getReturnedAttribute(): bool
    {
        return $this->is_returned ? true : false;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

    protected $dates = ['start_date', 'due_date'];

    protected $appends = [
        'milestone_label'
    ];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'author_id' => ['required', 'integer', 'exists:users,id'],
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'status' => ['required', 'integer', 'in:1,2,3'],
        'name' => ['required', 'string', 'max:255'],
        'start_date' => ['required', 'date'],
        'due_date' => ['required', 'date'],
        'description' => ['required', 'string', 'max:65553'],
    ];

    public const STATUSES = [
        1 => 'new',
        2 => 'in_progress',
        3 => 'complete',
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

    public function scopeStatus(Builder $query, int $type): Builder
    {
        return $query->where('status', $type);
    }
    public function getMilestoneLabelAttribute(): string
    {
        return $this->milestone ? $task->milestone->name : 'NaN';
    }
}

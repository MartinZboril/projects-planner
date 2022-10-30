<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $dates = ['start_date', 'due_date'];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'author_id' => ['required', 'integer', 'exists:users,id'],
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'status_id' => ['required', 'integer', 'exists:statuses,id'],
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

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }

    public function todos(): HasMany
    {
        return $this->hasMany(ToDo::class, 'task_id');
    }

    public function scopeNew($query): Builder
    {
        return $query->where('status_id', 1);
    }

    public function scopeInProgress($query): Builder
    {
        return $query->where('status_id', 2);
    }

    public function scopeCompleted($query): Builder
    {
        return $query->where('status_id', 3);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $dates = ['start_date', 'due_date'];

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

    public function scopeNew($query)
    {
        return $query->where('status_id', 1);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status_id', 2);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status_id', 3);
    }
}

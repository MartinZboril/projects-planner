<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

    protected $dates = ['due_date'];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'reporter_id' => ['required', 'integer', 'exists:users,id'],
        'assignee_id' => ['nullable', 'integer', 'exists:users,id'],
        'subject' => ['required', 'string', 'max:255'],
        'type' => ['required', 'integer', 'in:1,2,3,4'],
        'priority' => ['required', 'integer', 'in:1,2,3,4'],
        'status' => ['required', 'integer', 'in:1,2,3'],
        'due_date' => ['required', 'date'],
        'message' => ['required', 'max:65553'],
    ];

    public const STATUSES = [
        1 => 'open',
        2 => 'close',
        3 => 'archive',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function scopeDone(Builder $query): Builder
    {
        return $query->whereIn('status', [2, 3]);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereDate('due_date', '<=', date('Y-m-d'));
    }
}

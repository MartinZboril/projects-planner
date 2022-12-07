<?php

namespace App\Models;

use App\Enums\{TicketPriorityEnum, TicketTypeEnum, TicketStatusEnum};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

    protected $dates = ['due_date'];

    protected $appends = [
        'overdue'
    ];

    protected $casts = [
        'priority' => TicketPriorityEnum::class,
        'type' => TicketTypeEnum::class,
        'status' => TicketStatusEnum::class,
    ];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'reporter_id' => ['required', 'integer', 'exists:users,id'],
        'assignee_id' => ['nullable', 'integer', 'exists:users,id'],
        'subject' => ['required', 'string', 'max:255'],
        'type' => ['required', 'integer'],
        'priority' => ['required', 'integer'],
        'status' => ['required', 'integer'],
        'due_date' => ['required', 'date'],
        'message' => ['required', 'max:65553'],
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

    public function scopeStatus(Builder $query, TicketStatusEnum $type): Builder
    {
        return $query->where('status', $type);
    }

    public function scopeDone(Builder $query): Builder
    {
        return $query->whereIn('status', [TicketStatusEnum::close, TicketStatusEnum::archive]);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', TicketStatusEnum::open);
    }

    public function scopeUnassigned(Builder $query): Builder
    {
        return $query->whereNull('assignee_id');
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereDate('due_date', '<=', date('Y-m-d'));
    }

    public function getOverdueAttribute(): bool
    {
        return $this->due_date <= date('Y-m-d') && $this->status == TicketStatusEnum::open;
    }
}

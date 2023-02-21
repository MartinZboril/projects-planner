<?php

namespace App\Models;

use App\Traits\Scopes\{MarkedRecords, OverdueRecords};
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\{TicketPriorityEnum, TicketTypeEnum, TicketStatusEnum};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Ticket extends Model
{
    use HasFactory, MarkedRecords, OverdueRecords;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];
    
    protected $dates = ['due_date'];

    protected $appends = [
        'overdue',
        'assigned',
        'urgent',
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

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'tickets_files', 'ticket_id', 'file_id')->orderByDesc('created_at');
    }
        
    public function comments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'tickets_comments', 'ticket_id', 'comment_id')->orderByDesc('created_at');
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

    public function scopeAssigned(Builder $query): Builder
    {
        return $query->whereNotNull('assignee_id');
    }

    public function scopeUnassigned(Builder $query): Builder
    {
        return $query->whereNull('assignee_id');
    }

    public function getOverdueAttribute(): bool
    {
        return $this->due_date <= date('Y-m-d') && $this->status ===TicketStatusEnum::open;
    }
    
    public function getAssignedAttribute(): bool
    {
        return $this->assignee ? true : false;
    }

    public function getUrgentAttribute(): bool
    {
        return $this->priority === TicketPriorityEnum::urgent;
    }
}

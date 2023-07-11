<?php

namespace App\Models;

use App\Enums\TicketTypeEnum;
use App\Enums\TicketStatusEnum;
use App\Enums\TicketPriorityEnum;
use Spatie\Activitylog\LogOptions;
use App\Events\Ticket\TicketCreated;
use App\Events\Ticket\TicketDeleted;
use App\Traits\Scopes\MarkedRecords;
use App\Traits\Scopes\OverdueRecords;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory, MarkedRecords, OverdueRecords, SoftDeletes, LogsActivity;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected $dispatchesEvents = [
        'created' => TicketCreated::class,
        'deleted' => TicketDeleted::class,
    ];

    protected $casts = [
        'priority' => TicketPriorityEnum::class,
        'type' => TicketTypeEnum::class,
        'status' => TicketStatusEnum::class,
        'dued_at' => 'date',
    ];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'reporter_id' => ['required', 'integer', 'exists:users,id'],
        'assignee_id' => ['nullable', 'integer', 'exists:users,id'],
        'subject' => ['required', 'string', 'max:255'],
        'type' => ['required', 'integer'],
        'priority' => ['required', 'integer'],
        'status' => ['required', 'integer'],
        'dued_at' => ['required', 'date'],
        'message' => ['required', 'max:65553'],
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['subject', 'reporter_id', 'assignee', 'type', 'priority', 'dued_at', 'message'])
            ->dontLogIfAttributesChangedOnly(['assignee_id', 'status', 'is_converted', 'updated_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Ticket was {$eventName}.");
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id')->withTrashed();
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id')->withTrashed();
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

    public function task(): HasOne
    {
        return $this->hasOne(Task::class, 'ticket_id');
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

    protected function deadlineOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->dued_at <= date('Y-m-d') && $this->status === TicketStatusEnum::open,
        );
    }

    protected function assigned(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->assignee ? true : false,
        );
    }

    protected function urgent(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->priority === TicketPriorityEnum::urgent,
        );
    }
}

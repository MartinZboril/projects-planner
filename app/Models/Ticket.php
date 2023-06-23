<?php

namespace App\Models;

use App\Enums\TicketTypeEnum;
use App\Enums\TicketStatusEnum;
use App\Enums\TicketPriorityEnum;
use App\Traits\Scopes\MarkedRecords;
use App\Traits\Scopes\OverdueRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory, MarkedRecords, OverdueRecords, SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
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

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
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

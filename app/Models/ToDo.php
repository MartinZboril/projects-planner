<?php

namespace App\Models;

use App\Traits\Scopes\OverdueRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToDo extends Model
{
    use HasFactory, OverdueRecords, SoftDeletes;

    protected $table = 'todos';

    protected $fillable = [
        'task_id', 'name', 'dued_at', 'is_finished', 'description', 'is_finished',
    ];

    protected $casts = [
        'dued_at' => 'date',
    ];

    public const VALIDATION_RULES = [
        'task_id' => ['required', 'integer', 'exists:tasks,id'],
        'name' => ['required', 'string', 'max:255'],
        'dued_at' => ['required', 'date'],
        'is_finished' => ['boolean'],
        'description' => ['max:65553'],
    ];

    public const FINISH = 'finish';

    public const RETURN = 'return';

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function scopeFinished(Builder $query, bool $type): Builder
    {
        return $query->where('is_finished', $type);
    }

    protected function deadlineOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->dued_at <= date('Y-m-d') && ! $this->is_finished,
        );
    }
}

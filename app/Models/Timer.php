<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id', 'user_id', 'rate_id', 'since_at', 'until_at', 'note',
    ];

    protected $casts = [
        'since_at' => 'datetime',
        'until_at' => 'datetime',
    ];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'rate_id' => ['required', 'integer', 'exists:rates,id'],
        'since_at' => ['required', 'date_format:Y-m-d H:i'],
        'until_at' => ['required', 'date_format:Y-m-d H:i', 'after:since_at'],
        'note' => ['max:65553'],
    ];

    protected $appends = [
        'total_time',
        'amount',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function rate(): BelongsTo
    {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    public function scopeActive(Builder $query, bool $type): Builder
    {
        return ($type) ? $query->whereNull('until_at') : $query->whereNotNull('until_at');
    }

    protected function totalTime(): Attribute
    {
        return Attribute::make(
            get: function () {
                $since_at = Carbon::parse($this->since_at);
                $until_at = Carbon::parse($this->until_at);

                $diff = $since_at->diff($until_at);

                return round($diff->s / 3600 + $diff->i / 60 + $diff->h + $diff->days * 24, 2);
            },
        );
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn () => round($this->total_time * $this->rate->value, 2),
        );
    }

    protected function stopRoute(): Attribute
    {
        return Attribute::make(
            get: fn () => (! $this->until_at) ? route('projects.timers.stop', ['project' => $this->project, 'timer' => $this]) : '',
        );
    }

    protected function projectRoute(): Attribute
    {
        return Attribute::make(
            get: fn () => route('projects.show', $this->project),
        );
    }
}

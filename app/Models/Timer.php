<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timer extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'user_id', 'rate_id', 'since_at', 'until_at', 'note',
    ]; 

    protected $casts = [
        'since_at' => 'date',
        'until_at' => 'date',
    ];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'rate_id' => ['required', 'integer', 'exists:rates,id'],
        'since_at' => ['required', 'date_format:Y-m-d H:i'],
        'until_at' => ['required', 'date_format:Y-m-d H:i', 'after:since_at'],
        'note' => ['max:65553']
    ];

    protected $appends = [
        'total_time',
        'amount',
        'stop_route',
        'project_route',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rate(): BelongsTo
    {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    public function scopeActive(Builder $query, bool $type): Builder
    {
        return ($type) ? $query->whereNull('until_at') : $query->whereNotNull('until_at');
    }

    public function getTotalTimeAttribute(): float
    {
        $since_at = Carbon::parse($this->since_at);
        $until_at = Carbon::parse($this->until_at);
        
        $diff = $since_at->diff($until_at);
        
        return round($diff->s / 3600 + $diff->i / 60 + $diff->h + $diff->days * 24, 2);
    }

    public function getAmountAttribute(): float
    {
        return round($this->totalTime * $this->rate->value, 2);
    }

    public function getStopRouteAttribute(): string
    {
        return (!$this->until_at) ? route('projects.timers.stop', ['project' => $this->project, 'timer' => $this]) : '';
    }

    public function getProjectRouteAttribute(): string
    {
        return route('projects.show', $this->project);
    }
}

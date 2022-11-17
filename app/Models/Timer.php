<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timer extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

    protected $dates = ['since', 'until'];

    public const VALIDATION_RULES = [
        'project_id' => ['required', 'integer', 'exists:projects,id'],
        'user_id' => ['required', 'integer', 'exists:users,id'],
        'rate_id' => ['required', 'integer', 'exists:rates,id'],
        'since' => ['required', 'date_format:Y-m-d H:i'],
        'until' => ['required', 'date_format:Y-m-d H:i', 'after:since'],
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
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rate(): BelongsTo
    {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    public function scopeActive(Builder $query, bool $type): Builder
    {
        return ($type) ? $query->whereNull('until') : $query->whereNotNull('until');
    }

    public function getTotalTimeAttribute(): float
    {
        $since = Carbon::parse($this->since);
        $until = Carbon::parse($this->until);

        $diff = $since->diff($until);
        
        return round($diff->s / 3600 + $diff->i / 60 + $diff->h + $diff->days * 24, 2);
    }

    public function getAmountAttribute(): float
    {
        return round($this->totalTime * $this->rate->value, 2);
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timer extends Model
{
    use HasFactory;

    protected $dates = ['since', 'until'];

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

    public function scopeActive($query): Builder
    {
        return $query->whereNull('until');
    }

    public function getTotalTimeAttribute(): int
    {
        $since = Carbon::parse($this->since);
        $until = Carbon::parse($this->until);

        $diff = $since->diff($until);
        
        return round($diff->s / 3600 + $diff->i / 60 + $diff->h + $diff->days * 24, 2);
    }

    public function getAmountAttribute(): int
    {
        return round($this->totalTime * $this->rate->value);
    }
}

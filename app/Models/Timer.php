<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    use HasFactory;

    protected $dates = ['since', 'until'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('until');
    }

    public function getTotalTimeAttribute()
    {
        $since = Carbon::parse($this->since);
        $until = Carbon::parse($this->until);

        $diff = $since->diff($until);
        
        return round($diff->s / 3600 + $diff->i / 60 + $diff->h + $diff->days * 24, 2);
    }
}

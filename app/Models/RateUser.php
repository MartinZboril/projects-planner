<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RateUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rate_user';

    public function rate(): BelongsTo
    {
        return $this->belongsTo(Rate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

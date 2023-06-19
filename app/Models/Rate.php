<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'value', 'is_active', 'note',
    ];

    public const VALIDATION_RULES = [
        'name' => ['required', 'max:255'],
        'value' => ['required', 'integer', 'min:0'],
        'is_active' => ['boolean'],
        'note' => ['max:65553'],
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rate_user', 'rate_id', 'user_id');
    }
}

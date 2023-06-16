<?php

namespace App\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait NewestRecords
{
    /**
     * Order records by newest.
     */
    public function scopeNewest(Builder $query): Builder
    {
        return $query->orderBy($this->getTable().'.created_at', 'desc')->orderBy($this->getTable().'.id', 'desc');
    }
}

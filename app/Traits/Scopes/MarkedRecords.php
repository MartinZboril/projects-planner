<?php

namespace App\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait MarkedRecords
{
    /**
     * Only records that are marked.
     */
    public function scopeMarked(Builder $query): Builder
    {
        return $query->where($this->getTable().'.is_marked', true);
    }
}

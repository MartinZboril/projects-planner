<?php

namespace App\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait OverdueRecords
{
    /**
     * Only records that are overdue.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where($this->getTable().'.dued_at', '<=', date('Y-m-d'));
    }
}

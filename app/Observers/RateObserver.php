<?php

namespace App\Observers;

use App\Models\Rate;
use App\Models\RateUser;

class RateObserver
{
    /**
     * Handle the Rate "deleted" event.
     */
    public function deleted(Rate $rate): void
    {
        RateUser::where('rate_id', $rate->id)->delete();
    }
}

<?php

namespace App\Services\Analysis;

use App\Models\Ticket;
use Illuminate\Support\Collection;

class TicketAnalysis
{
    /**
     * Get analyze for tickets.
     */
    public function getAnalyze(): Collection
    {
        return Ticket::all();
    }
}
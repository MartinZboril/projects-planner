<?php

namespace App\Observers;

use App\Models\File;
use App\Models\Ticket;

class TicketObserver
{
    public function deleted(Ticket $ticket): void
    {
        $ticket->files()->delete();
        File::where('fileable_type', 'App\Models\Comment')->whereIn('fileable_id', array_column($ticket->comments->toArray(), 'id'))->delete();
        $ticket->comments()->delete();
    }
}

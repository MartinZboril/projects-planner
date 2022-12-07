    <!-- ticket status change forms -->
    @include('tickets.forms.change', ['id' => 'open-ticket-' . $ticket->id . '-form', 'ticket' => $ticket, 'status' => App\Enums\TicketStatusEnum::open->value, 'redirect' => 'tickets'])    
    @include('tickets.forms.change', ['id' => 'close-ticket-' . $ticket->id . '-form', 'ticket' => $ticket, 'status' => App\Enums\TicketStatusEnum::close->value, 'redirect' => 'tickets'])    
    @include('tickets.forms.change', ['id' => 'archive-ticket-' . $ticket->id . '-form', 'ticket' => $ticket, 'status' => App\Enums\TicketStatusEnum::archive->value, 'redirect' => 'tickets'])    
    <!-- convert ticket to task form -->
    @include('tickets.forms.convert', ['id' => 'convert-ticket-' . $ticket->id . '-to-task-form', 'ticket' => $ticket, 'redirect' => 'tickets']) 

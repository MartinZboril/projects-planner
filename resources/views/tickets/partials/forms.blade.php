    <!-- ticket status change forms -->
    @include('tickets.forms.change', ['id' => 'open-ticket-' . $ticket->id . '-form', 'ticket' => $ticket, 'status' => 1, 'redirect' => 'tickets'])    
    @include('tickets.forms.change', ['id' => 'close-ticket-' . $ticket->id . '-form', 'ticket' => $ticket, 'status' => 2, 'redirect' => 'tickets'])    
    @include('tickets.forms.change', ['id' => 'archive-ticket-' . $ticket->id . '-form', 'ticket' => $ticket, 'status' => 3, 'redirect' => 'tickets'])    
    <!-- convert ticket to task form -->
    @include('tickets.forms.convert', ['id' => 'convert-ticket-' . $ticket->id . '-to-task-form', 'ticket' => $ticket, 'redirect' => 'tickets']) 

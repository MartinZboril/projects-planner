<!-- ticket status change forms -->
@if (in_array($ticket->status, [App\Enums\TicketStatusEnum::close, App\Enums\TicketStatusEnum::archive]))
    @include('tickets.forms.change', ['id' => 'open-ticket-' . $ticket->id . '-form', 'status' => App\Enums\TicketStatusEnum::open->value])    
@endif
@if ($ticket->status == App\Enums\TicketStatusEnum::open)
    @include('tickets.forms.change', ['id' => 'close-ticket-' . $ticket->id . '-form', 'status' => App\Enums\TicketStatusEnum::close->value])    
@endif
@if ($ticket->status == App\Enums\TicketStatusEnum::open)
    @include('tickets.forms.change', ['id' => 'archive-ticket-' . $ticket->id . '-form', 'status' => App\Enums\TicketStatusEnum::archive->value])    
@endif
@if ($ticket->status == App\Enums\TicketStatusEnum::open && $ticket->assigned)
    <!-- convert ticket to task form -->
    @include('tickets.forms.convert', ['id' => 'convert-ticket-' . $ticket->id . '-to-task-form']) 
@endif
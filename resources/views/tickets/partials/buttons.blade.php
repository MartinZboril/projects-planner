@if (!$ticket->is_convert && $ticket->assignee_id && $ticket->status != App\Enums\TicketStatusEnum::close && $ticket->status != App\Enums\TicketStatusEnum::archive)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="event.preventDefault(); document.getElementById('convert-ticket-{{ $ticket->id }}-to-task-form').submit();">@include('site.partials.icon', ['icon' => 'fas fa-tasks', 'text' => $buttonText ? 'Convert to task' : ''])</a>
@endif
@if ($ticket->status == App\Enums\TicketStatusEnum::open)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-success" onclick="event.preventDefault(); document.getElementById('close-ticket-{{ $ticket->id }}-form').submit();">@include('site.partials.icon', ['icon' => 'fas fa-check', 'text' => $buttonText ? 'Close' : ''])</a>
@elseif ($ticket->status == App\Enums\TicketStatusEnum::close || $ticket->status == App\Enums\TicketStatusEnum::archive)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-info" onclick="event.preventDefault(); document.getElementById('open-ticket-{{ $ticket->id }}-form').submit();">@include('site.partials.icon', ['icon' => 'fas fa-bell', 'text' => $buttonText ? 'Open' : ''])</a>
@endif
@if ($ticket->status != App\Enums\TicketStatusEnum::close && $ticket->status != App\Enums\TicketStatusEnum::archive)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="event.preventDefault(); document.getElementById('archive-ticket-{{ $ticket->id }}-form').submit();">@include('site.partials.icon', ['icon' => 'fas fa-archive', 'text' => ''])</a>
@endif
<!-- Tickets forms -->
@include('tickets.partials.forms', ['ticket' => $ticket])
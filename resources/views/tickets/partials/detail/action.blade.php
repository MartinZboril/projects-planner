@if (!$ticket->is_convert && $ticket->assignee_id && $ticket->status != 2 && $ticket->status != 3)
    <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('convert-ticket-to-task-form').submit();"><i class="fas fa-tasks mr-1"></i> Convert to task</a>
@endif
@if ($ticket->status == 1)
    <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('close-ticket-form').submit();"><i class="fas fa-check mr-1"></i> Close</a>
@elseif ($ticket->status == 2 || $ticket->status == 3)
    <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('open-ticket-form').submit();"><i class="fas fa-bell mr-1"></i> Open</a>
@endif
@if ($ticket->status != 2 && $ticket->status != 3)
    <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('archive-ticket-form').submit();"><i class="fas fa-archive"></i></a>
@endif
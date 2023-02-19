<form id="{{ $id }}" action="{{ route('projects.tickets.convert_to_task', ['project' => $ticket->project, 'ticket' => $ticket]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
<form id="{{ $id }}" action="{{ route('projects.tickets.mark', ['project' => $project, 'ticket' => $ticket]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
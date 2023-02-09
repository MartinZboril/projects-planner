<form id="{{ $id }}" action="{{ route('projects.tickets.change_status', ['project' => $project, 'ticket' => $ticket]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="{{ $status }}">
</form>
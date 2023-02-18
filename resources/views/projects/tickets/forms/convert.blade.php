<form id="{{ $id }}" action="{{ route('projects.tickets.convert_to_task', ['project' => $ticket->project, 'ticket' => $ticket]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="project_id" value="{{ $ticket->project_id }}">
    <input type="hidden" name="author_id" value="{{ $ticket->reporter_id }}">
    <input type="hidden" name="user_id" value="{{ $ticket->assignee_id }}">
    <input type="hidden" name="name" value="{{ $ticket->subject }}">
    <input type="hidden" name="start_date" value="{{ $ticket->due_date }}">
    <input type="hidden" name="due_date" value="{{ $ticket->due_date }}">
    <input type="hidden" name="description" value="{{ $ticket->message }}">
</form>
<form id="{{ $id }}" action="{{ route('tickets.convert', $ticket->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="redirect" value="{{ $redirect == 'projects' ? 'projects' : 'tasks' }}">
</form>
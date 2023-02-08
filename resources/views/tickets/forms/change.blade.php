<form id="{{ $id }}" action="{{ route('tickets.change_status', $ticket->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="{{ $status }}">
</form>
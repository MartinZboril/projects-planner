<form id="{{ $id }}" action="{{ route('tickets.change', $ticket->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="{{ $status }}">
    <input type="hidden" name="redirect" value="{{ $redirect }}">
</form>
<form id="{{ $id }}" action="{{ route('tickets.mark', $ticket->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
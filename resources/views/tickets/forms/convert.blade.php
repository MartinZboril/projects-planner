<form id="{{ $id }}" action="{{ route('tickets.convert', $ticket->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
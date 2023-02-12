<form id="{{ $id }}" action="{{ route('tickets.mark', $ticket) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
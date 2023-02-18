<form id="{{ $id }}" action="{{ route('tickets.convert_to_task', $ticket) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
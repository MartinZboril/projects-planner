<form id="{{ $id }}" action="{{ route('notes.mark', $note) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
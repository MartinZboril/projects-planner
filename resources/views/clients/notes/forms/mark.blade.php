<form id="{{ $id }}" action="{{ route('clients.notes.mark', ['client' => $client, 'note' => $note]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
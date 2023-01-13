<form id="{{ $id }}" action="{{ route('clients.mark', $client->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
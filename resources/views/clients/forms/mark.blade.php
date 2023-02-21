<form id="{{ $id }}" action="{{ route('clients.mark', $client) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
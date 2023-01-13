<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="event.preventDefault(); document.getElementById('{{ ($client->is_marked ? 'unmark' : 'mark') . '-client-' . $client->id . '-form'}}').submit();">
    <i class="{{ ($client->is_marked ? 'fas' : 'far') }} fa-bookmark"></i>
</a>
@include('clients.forms.mark', ['id' => ($client->is_marked ? 'unmark' : 'mark') . '-client-' . $client->id . '-form', 'client' => $client])
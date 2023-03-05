<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="markClient('{{ route('clients.mark', $client) }}', '{{  $type }}')">
    <i class="{{ ($client->is_marked ? 'fas' : 'far') }} fa-bookmark" id="client-{{ $client->id }}-marked"></i>
</a>
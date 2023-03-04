<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="markClient('{{ route('clients.mark', $client) }}')">
    <i class="{{ ($client->is_marked ? 'fas' : 'far') }} fa-bookmark" id="client-{{ $client->id }}-marked"></i>
</a>
<script src="{{ asset('js/actions/client.js') }}" defer></script>
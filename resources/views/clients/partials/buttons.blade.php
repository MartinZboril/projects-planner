<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="markClient('{{ route('clients.mark', $client) }}', '{{  $type }}', '{{ $tableIdentifier }}')">
    <i class="{{ ($client->is_marked ? 'fas' : 'far') }} fa-bookmark" id="client-{{ $client->id }}-marked"></i>
</a>
<a href="#" class="btn btn-{{ $buttonSize }} btn-danger" onclick="deleteClient('{{ route('clients.destroy', $client) }}', '{{ $type }}', '{{ $tableIdentifier }}', '{{ $redirect }}')">
    <x-site.ui.icon icon="fas fa-trash" :text="$hideButtonText ?? 'Delete'" />
</a>
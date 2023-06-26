<a href="#" class="btn btn-{{ $buttonSize }} btn-danger" onclick="deleteUser('{{ route('users.destroy', $user) }}', '{{ $type }}', '{{ $tableIdentifier }}', '{{ $redirect }}')">
    <x-site.ui.icon icon="fas fa-trash" :text="$hideButtonText ?? 'Delete'" />
</a>
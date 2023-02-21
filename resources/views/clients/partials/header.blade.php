<ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link{{ $active === 'client' ? ' active' : '' }}" href="{{ route('clients.show', $client) }}">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link{{ $active === 'comment' ? ' active' : '' }}" href="{{ route('clients.comments.index', $client) }}">Comments</a></li>
    <li class="nav-item"><a class="nav-link{{ $active === 'file' ? ' active' : '' }}" href="{{ route('clients.files.index', $client) }}">Files</a></li>
    <li class="nav-item"><a class="nav-link{{ $active === 'note' ? ' active' : '' }}" href="{{ route('clients.notes.index', $client) }}">Notes</a></li>
</ul>
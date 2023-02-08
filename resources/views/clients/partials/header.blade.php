<ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link @if($active == 'client'){{ 'active' }}@endif" href="{{ route('clients.show', $client->id) }}">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'comment'){{ 'active' }}@endif" href="{{ route('clients.comments.index', $client->id) }}">Comments</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'file'){{ 'active' }}@endif" href="{{ route('clients.files.index', $client->id) }}">Files</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'note'){{ 'active' }}@endif" href="{{ route('clients.notes.index', $client->id) }}">Notes</a></li>
</ul>
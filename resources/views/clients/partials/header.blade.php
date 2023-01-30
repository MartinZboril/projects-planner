<ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link @if($active == 'client'){{ 'active' }}@endif" href="{{ route('clients.detail', $client->id) }}">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'comment'){{ 'active' }}@endif" href="{{ route('clients.comments', $client->id) }}">Comments</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'file'){{ 'active' }}@endif" href="{{ route('clients.files', $client->id) }}">Files</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'note'){{ 'active' }}@endif" href="{{ route('clients.notes', $client->id) }}">Notes</a></li>
</ul>
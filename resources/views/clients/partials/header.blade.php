<ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link @if($active === 'client'){{ 'active' }}@endif" href="{{ route('clients.show', $client) }}">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link @if($active === 'comment'){{ 'active' }}@endif" href="{{ route('clients.comments.index', $client) }}">Comments</a></li>
    <li class="nav-item"><a class="nav-link @if($active === 'file'){{ 'active' }}@endif" href="{{ route('clients.files.index', $client) }}">Files</a></li>
    <li class="nav-item"><a class="nav-link @if($active === 'note'){{ 'active' }}@endif" href="{{ route('clients.notes.index', $client) }}">Notes</a></li>
</ul>
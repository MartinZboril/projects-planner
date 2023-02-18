<ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link @if($active == 'project'){{ 'active' }}@endif" href="{{ route('projects.show', $project) }}">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'task'){{ 'active' }}@endif" href="{{ route('projects.tasks.index', $project) }}">Tasks</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'kanban'){{ 'active' }}@endif" href="{{ route('projects.tasks.kanban', $project) }}">Kanban</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'milestone'){{ 'active' }}@endif" href="{{ route('projects.milestones.index', $project) }}">Milestones</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'timesheets'){{ 'active' }}@endif" href="{{ route('projects.timers.index', $project) }}">Timesheets</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'ticket'){{ 'active' }}@endif" href="{{ route('projects.tickets.index', $project) }}">Tickets</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'comment'){{ 'active' }}@endif" href="{{ route('projects.comments.index', $project) }}">Comments</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'file'){{ 'active' }}@endif" href="{{ route('projects.files.index', $project) }}">Files</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'note'){{ 'active' }}@endif" href="{{ route('projects.notes.index', $project) }}">Notes</a></li>
</ul>
<ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link @if($active == 'project'){{ 'active' }}@endif" href="{{ route('projects.show', $project->id) }}">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'task'){{ 'active' }}@endif" href="{{ route('projects.tasks', $project->id) }}">Tasks</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'kanban'){{ 'active' }}@endif" href="{{ route('projects.kanban', $project->id) }}">Kanban</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'milestone'){{ 'active' }}@endif" href="{{ route('projects.milestones', $project->id) }}">Milestones</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'timesheets'){{ 'active' }}@endif" href="{{ route('projects.timesheets', $project->id) }}">Timesheets</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'ticket'){{ 'active' }}@endif" href="{{ route('projects.tickets', $project->id) }}">Tickets</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'comment'){{ 'active' }}@endif" href="{{ route('projects.comments.index', $project->id) }}">Comments</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'file'){{ 'active' }}@endif" href="{{ route('projects.files.index', $project->id) }}">Files</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'note'){{ 'active' }}@endif" href="{{ route('projects.notes.index', $project->id) }}">Notes</a></li>
</ul>
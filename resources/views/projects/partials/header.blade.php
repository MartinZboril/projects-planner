<ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link @if($active == 'project'){{ 'active' }}@endif" href="{{ route('projects.detail', $project->id) }}">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'task'){{ 'active' }}@endif" href="{{ route('projects.tasks', $project->id) }}">Tasks</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'kanban'){{ 'active' }}@endif" href="{{ route('projects.kanban', $project->id) }}">Kanban</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'milestone'){{ 'active' }}@endif" href="{{ route('projects.milestones', $project->id) }}">Milestones</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'timesheets'){{ 'active' }}@endif" href="{{ route('projects.timesheets', $project->id) }}">Timesheets</a></li>
    <li class="nav-item"><a class="nav-link @if($active == 'ticket'){{ 'active' }}@endif" href="{{ route('projects.tickets', $project->id) }}">Tickets</a></li>
</ul>
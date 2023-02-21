<ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link{{ $active === 'project' ? ' active' : '' }}" href="{{ route('projects.show', $project) }}">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link{{ $active === 'task' ? ' active' : '' }}" href="{{ route('projects.tasks.index', $project) }}">Tasks</a></li>
    <li class="nav-item"><a class="nav-link{{ $active === 'kanban' ? ' active' : '' }}" href="{{ route('projects.tasks.kanban', $project) }}">Kanban</a></li>
    <li class="nav-item"><a class="nav-link{{ $active === 'milestone' ? ' active' : '' }}" href="{{ route('projects.milestones.index', $project) }}">Milestones</a></li>
    <li class="nav-item"><a class="nav-link{{ $active === 'timesheets' ? ' active' : '' }}" href="{{ route('projects.timers.index', $project) }}">Timesheets</a></li>
    <li class="nav-item"><a class="nav-link{{ $active === 'ticket' ? ' active' : '' }}" href="{{ route('projects.tickets.index', $project) }}">Tickets</a></li>
    <li class="nav-item"><a class="nav-link{{ $active === 'comment' ? ' active' : '' }}" href="{{ route('projects.comments.index', $project) }}">Comments</a></li>
    <li class="nav-item"><a class="nav-link{{ $active === 'file' ? ' active' : '' }}" href="{{ route('projects.files.index', $project) }}">Files</a></li>
    <li class="nav-item"><a class="nav-link{{ $active === 'note' ? ' active' : '' }}" href="{{ route('projects.notes.index', $project) }}">Notes</a></li>
</ul>
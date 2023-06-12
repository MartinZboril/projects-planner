<div class="card card-primary card-outline">
    <div class="card-header">
        {{ $milestone->name }}
        <span class="badge badge-{{ $milestone->progress === 1 ? 'success' : 'warning' }} ml-1" style="font-size:14px;">{{ $milestone->progress === 1 ? 'Completed' : 'In progress' }}</span>
        @if($milestone->deadline_overdue)<span class="badge badge-danger ml-1" style="font-size:14px;">Overdue</span>@endif
    </div>
    <div class="card-body">
        <span class="d-block">Project: <b><a href="{{ route('projects.show', $milestone->project->id) }}">{{ $milestone->project->name }}</a></b></span>
        <span class="d-block">User: <b><a href="{{ route('users.show', $milestone->owner) }}">{{ $milestone->owner->full_name }}</a></b></span>
        <span class="d-block">Start date: <b>{{ $milestone->started_at->format('d.m.Y') }}</b></span>
        <span class="d-block">End date: <b>{{ $milestone->dued_at->format('d.m.Y') }}</b></span>
        <span class="d-block">Tasks: <b>{{ $milestone->tasksCompleted->count() }}/{{ $milestone->tasks->count() }}</b><span class="badge badge-{{ $milestone->progress === 1 ? 'success' : 'warning' }} ml-1">{{ $milestone->progress * 100 }} % Complete</span></span>
        <hr>
        {!! $milestone->description !!}
    </div>
</div>
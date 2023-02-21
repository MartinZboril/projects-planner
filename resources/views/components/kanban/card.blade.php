<div class="card card-{{ $colour }}">
    <div class="card-header">{{ $label }}</div>
    <div class="card-body">
        @forelse ($tasks as $task)
            <div class="card card-{{ $colour }} card-outline">
                <div class="card-header">
                    <x-kanban.header :$task />
                </div>
                <div class="card-body">
                    <span class="d-block">Due date: <span class="btn btn-sm btn-outline-{{ $task->overdue ? 'danger' : 'secondary' }} disabled mb-1" style="font-size:14px;">{{ $task->due_date->format('d.m.Y') }}</span></span>
                    <span class="d-block">User: <b><a href="{{ route('users.show', $task->user) }}">{{ $task->user->full_name }}</a></b></span>
                    <span class="d-block">Author: <b><a href="{{ route('users.show', $task->author) }}">{{ $task->author->full_name }}</a></b></span>
                    <span class="d-block">Milestone: <b>@if($task->milestone)<a href="{{ route('projects.milestones.show', ['project' => $task->milestone->project, 'milestone' => $task->milestone]) }}">{{ $task->milestone_label }}</a>@else{{ $task->milestone_label }}@endif</b></span>
                </div>
            </div>
            <x-kanban.forms :$task />
        @empty
            <div class="card">
                <div class="card-header">There are no tasks!</div>
            </div>
        @endforelse
    </div>
</div>
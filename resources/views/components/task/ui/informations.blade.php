<div class="card card-primary card-outline">
    <div class="card-header">
        {{ $task->name }}
        <x-task.ui.status-badge :text="false" :$task />
        @if($task->overdue)<span class="badge badge-danger ml-1" style="font-size:14px;">Overdue</span>@endif
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-sm-4">
                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text text-center text-muted">Due date</span>
                        <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-{{ $task->overdue ? 'danger' : 'secondary' }}">{{ $task->due_date->format('d.m.Y') }}</span></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text text-center text-muted">Start date</span>
                        <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-success">{{ $task->start_date->format('d.m.Y') }}</span></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text text-center text-muted">Created at</span>
                        <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-secondary">{{ $task->created_at->format('d.m.Y') }}</span></span>
                    </div>
                </div>
            </div>
        </div>
        <span class="d-block">Project: <b><a href="{{ route('projects.show', $task->project) }}">{{ $task->project->name }}</a></b></span>
        <span class="d-block">Client: <b><a href="{{ route('clients.show', $task->project->client) }}">{{ $task->project->client->name }}</a></b></span>
        @if($task->milestone)
            <span class="d-block">Milestone: <b><a href="{{ route('projects.milestones.show', ['project' => $task->milestone->project, 'milestone' => $task->milestone]) }}">{{ $task->milestone_label }}</a></b></span>
        @endif
        <span class="d-block">User: <b><a href="{{ route('users.show', $task->user) }}">{{ $task->user->full_name }}</a></b></span>
        <span class="d-block">Author: <b><a href="{{ route('users.show', $task->author) }}">{{ $task->author->full_name }}</a></b></span>
        <span class="d-block">Status: <b><x-task.ui.status-badge :text="true" :$task /></b></span>
        <hr>
        {!! $task->description !!}
    </div>
</div>
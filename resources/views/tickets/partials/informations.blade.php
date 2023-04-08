<div class="card card-primary card-outline">
    <div class="card-header">
        {{ $ticket->subject }}
        <span id="ticket-status-badge">
            <x-ticket.ui.status-badge :text="false" :status="$ticket->status" />
        </span>         
        <span style="{{ $ticket->overdue ? '' : 'display: none;' }}font-size:14px;" id="ticket-{{ $ticket->id }}-overdue-badge" class="badge badge-danger ml-1">Overdue</span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-sm-4">
                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text text-center text-muted">Due date</span>
                        <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-{{ $ticket->overdue ? 'danger' : 'secondary' }}" id="ticket-{{ $ticket->id }}-due-date">{{ $ticket->due_date->format('d.m.Y') }}</span></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text text-center text-muted">Created at</span>
                        <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-secondary">{{ $ticket->created_at->format('d.m.Y') }}</span></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text text-center text-muted">Assigned</span>
                        <span class="info-box-number text-center text-muted mb-0">@if($ticket->assignee_id)<b><a href="{{ route('users.show', $ticket->assignee) }}">{{ $ticket->assignee->full_name }}</a></b>@else{{ 'NaN' }}@endif</span>
                    </div>
                </div>
            </div>
        </div>
        <span class="d-block">Project: <b><a href="{{ route('projects.show', $ticket->project) }}">{{ $ticket->project->name }}</a></b></span>
        <span class="d-block">Client: <b><a href="{{ route('clients.show', $ticket->project->client) }}">{{ $ticket->project->client->name }}</a></b></span>
        @if($ticket->task)<span class="d-block">Converted Task: <b><a href="{{ route(($project ?? false) ? 'projects.tasks.show' : 'tasks.show', ($project ?? false) ? ['project' => $ticket->project, 'task' => $ticket->task] : $ticket->task) }}">{{ $ticket->task->name }}</a></b></span>@endif
        <span class="d-block">Reporter: <b><a href="{{ route('users.show', $ticket->reporter) }}">{{ $ticket->reporter->full_name }}</a></b></span>
        @if($ticket->assignee_id)<span class="d-block">Assigned: <b><a href="{{ route('users.show', $ticket->assignee) }}">{{ $ticket->assignee->full_name }}</a></b></span>@endif
        <span class="d-block">Status: <b id="ticket-status-text"><x-ticket.ui.status-badge :text="true" :status="$ticket->status" /></b></span>
        <span class="d-block">Priority: <b class="text-{{ $ticket->urgent ? 'danger' : 'body' }}"><x-ticket.ui.priority :priority="$ticket->priority" /></b></span>
        <span class="d-block">Type: <b><x-ticket.ui.type :type="$ticket->type" /></b></span>
        <hr>
        {!! $ticket->message !!}
    </div>
</div>
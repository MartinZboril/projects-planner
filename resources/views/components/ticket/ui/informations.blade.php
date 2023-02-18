<div class="card card-primary card-outline">
    <div class="card-header">
        {{ $ticket->subject }}
        <x-ticket.ui.status-badge :text="false" :status="$ticket->status" />
        @if($ticket->overdue)<span class="badge badge-danger ml-1" style="font-size:14px;">Overdue</span>@endif
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-sm-4">
                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text text-center text-muted">Due date</span>
                        <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-{{ $ticket->overdue ? 'danger' : 'secondary' }}">{{ $ticket->due_date->format('d.m.Y') }}</span></span>
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
                        <span class="info-box-text text-center text-muted">Status</span>
                        <span class="info-box-number text-center text-muted mb-0"><x-ticket.ui.status-badge :text="false" :status="$ticket->status" /></span>
                    </div>
                </div>
            </div>
        </div>
        <span class="d-block">Project: <b><a href="{{ route('projects.show', $ticket->project) }}">{{ $ticket->project->name }}</a></b></span>
        <span class="d-block">Client: <b><a href="{{ route('clients.show', $ticket->project->client) }}">{{ $ticket->project->client->name }}</a></b></span>
        <span class="d-block">Reporter: <b><a href="{{ route('users.show', $ticket->reporter) }}">{{ $ticket->reporter->full_name }}</a></b></span>
        @if($ticket->assignee_id)<span class="d-block">Assigned: <b><a href="{{ route('users.show', $ticket->assignee) }}">{{ $ticket->assignee->full_name }}</a></b></span>@endif
        <span class="d-block">Status: <b><x-ticket.ui.status-badge :text="false" :status="$ticket->status" /></b></span>
        <span class="d-block">Priority: <b class="text-{{ $ticket->urgent ? 'danger' : 'body' }}"><x-ticket.ui.priority :priority="$ticket->priority" /></b></span>
        <span class="d-block">Type: <b><x-ticket.ui.type :type="$ticket->type" /></b></span>
        <hr>
        {!! $ticket->message !!}
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="card card-primary card-outline rounded-0">
            <div class="card-header">{{ $ticket->subject }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Due date</span>
                                <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-danger">{{ $ticket->due_date->format('d.m.Y') }}</span></span>
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
                                <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-info">@include('tickets.partials.status', ['status' => $ticket->status])</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="d-block">Project: <b>{{ $ticket->project->name }}</b></span>
                <span class="d-block">Client: <b>{{ $ticket->project->client->name }}</b></span>
                <span class="d-block">Reporter: <b>{{ $ticket->reporter->full_name }}</b></span>
                @if ($ticket->assignee)
                    <span class="d-block">Author: <b>{{ $ticket->assignee->full_name }}</b></span>
                @endif
                <span class="d-block">Status: <b>@include('tickets.partials.status', ['status' => $ticket->status])</b></span>
                <span class="d-block">Priority: <b>@include('tickets.partials.priority', ['priority' => $ticket->priority])</b></span>
                <span class="d-block">Type: <b>@include('tickets.partials.type', ['type' => $ticket->type])</b></span>
                <hr>
                {!! $ticket->message !!}
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card card-primary card-outline rounded-0">
            <div class="card-header">Activity Feed</div>
            <div class="card-body">
            </div>
        </div>
    </div>
</div> 
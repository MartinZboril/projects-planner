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
                                <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-info">{{ $ticket->status == 1 ? 'Open' : ($ticket->status == 2 ? 'Closed' : ($ticket->status == 3 ? 'Archived' : $ticket->status)) }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="d-block">Project: <b>{{ $ticket->project->name }}</b></span>
                <span class="d-block">Client: <b>{{ $ticket->project->client->name }}</b></span>
                <span class="d-block">Reporter: <b>{{ $ticket->reporter->name }} {{ $ticket->reporter->surname }}</b></span>
                @if ($ticket->assignee)
                    <span class="d-block">Author: <b>{{ $ticket->assignee->name }} {{ $ticket->assignee->surname }}</b></span>
                @endif
                <span class="d-block">Status: <b>{{ $ticket->status == 1 ? 'Open' : ($ticket->status == 2 ? 'Closed' : ($ticket->status == 3 ? 'Archived' : $ticket->status)) }}</b></span>
                <span class="d-block">Priority: <b>{{ $ticket->priority == 1 ? 'Error' : ($ticket->priority == 2 ? 'Inovation' : ($ticket->priority == 3 ? 'Help' : ($ticket->priority == 4 ? 'Other' : $ticket->priority))) }}</b></span>
                <span class="d-block">Type: <b>{{ $ticket->type == 1 ? 'Low' : ($ticket->type == 2 ? 'Medium' : ($ticket->type == 3 ? 'High' : ($ticket->type == 4 ? 'Urgent' : $ticket->type))) }}</b></span>
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
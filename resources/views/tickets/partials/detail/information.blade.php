<div class="row">
    <div class="col-md-5">
        <div class="card card-primary card-outline">
            <div class="card-header">
                {{ $ticket->subject }}
                <span class="badge badge-@include('tickets.partials.colour', ['status' => $ticket->status]) ml-2" style='font-size:14px;'>@include('tickets.partials.status', ['status' => $ticket->status])</span>
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
                                <span class="info-box-number text-center text-muted mb-0"><span class="badge badge-@include('tickets.partials.colour', ['status' => $ticket->status])">@include('tickets.partials.status', ['status' => $ticket->status])</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="d-block">Project: <b><a href="{{ route('projects.detail', $ticket->project->id) }}">{{ $ticket->project->name }}</a></b></span>
                <span class="d-block">Client: <b><a href="{{ route('clients.detail', $ticket->project->client->id) }}">{{ $ticket->project->client->name }}</a></b></span>
                <span class="d-block">Reporter: <b><a href="{{ route('users.detail', $ticket->reporter->id) }}">{{ $ticket->reporter->full_name }}</a></b></span>
                @if($ticket->assignee_id)<span class="d-block">Assigned: <b><a href="{{ route('users.detail', $ticket->assignee->id) }}">{{ $ticket->assignee->full_name }}</a></b></span>@endif
                <span class="d-block">Status: <b>@include('tickets.partials.status', ['status' => $ticket->status])</b></span>
                <span class="d-block">Priority: <b class="text-{{ $ticket->priority == App\Enums\TicketPriorityEnum::urgent ? 'danger' : 'body' }}">@include('tickets.partials.priority', ['priority' => $ticket->priority])</b></span>
                <span class="d-block">Type: <b>@include('tickets.partials.type', ['type' => $ticket->type])</b></span>
                <hr>
                {!! $ticket->message !!}
            </div>
        </div>
        <div class="card card-primary card-outline">
            <div class="card-header">Activity Feed</div>
            <div class="card-body">
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">Files</div>
            <div class="card-body">
                @include('files.list', ['files' => $ticket->files, 'parentId' => $ticket->id, 'parentType' => 'ticket'])
            </div>
        </div>
        <div class="card card-primary card-outline">
            <div class="card-header">Comments</div>
            <div class="card-body">
                @include('comments.list', ['comment' => $comment, 'comments' => $ticket->comments, 'parentId' => $ticket->id, 'parentType' => 'ticket'])
            </div>
        </div>
    </div>
</div> 
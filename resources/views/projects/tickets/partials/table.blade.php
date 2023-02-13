<div class="table-responsive">
    <table id="@if($tickets->count() > 0){{ $id }}@endif" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Project</th>
                <th>Reporter</th>
                <th>Assignee</th>
                <th>Date</th>
                <th>Status</th>
                <th>Type</th>
                <th>Priority</th>
                <th>Due Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tickets as $ticket)
                <tr>
                    <td><a href="{{ route('projects.tickets.show', ['project' => $project, 'ticket' => $ticket]) }}">{{ $ticket->subject }}</a></td>
                    <td><a href="{{ route('projects.show', $ticket->project->id) }}">{{ $ticket->project->name }}</a></td>
                    <td>@include('site.partials.user', ['user' => $ticket->reporter])</td>
                    <td>
                        @if($ticket->assignee)                                            
                            @include('site.partials.user', ['user' => $ticket->assignee])
                        @else
                            NaN
                        @endif
                    </td>
                    <td>{{ $ticket->created_at->format('d.m.Y') }}</td>
                    <td><x-ticket.ui.status-badge :text="true" :status="$ticket->status" /></td>
                    <td><x-ticket.ui.type :type="$ticket->type" /></td>
                    <td><span class="text-{{ $ticket->urgent ? 'danger font-weight-bold' : 'body' }}"><x-ticket.ui.priority :priority="$ticket->priority" /></span></td>
                    <td><span class="text-{{ $ticket->overdue ? 'danger' : 'body' }}">{{ $ticket->due_date->format('d.m.Y') }}</span></td>
                    <td>
                        <a href="{{ route('projects.tickets.edit', ['project' => $project, 'ticket' => $ticket]) }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ route('projects.tickets.show', ['project' => $project, 'ticket' => $ticket]) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                        @include('tickets.partials.buttons', ['project' => $project, 'ticket' => $ticket, 'buttonSize' => 'xs', 'buttonText' => false])               
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No tickets were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>  
</div>
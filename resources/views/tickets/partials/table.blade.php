<div class="table-responsive">
    <table id="@if($tickets->count() > 0){{ $id }}@endif" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Subject</th>
                @if(in_array('project', $display))<th>Project</th>@endif
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
                    <td><a href="{{ $redirect == 'project' ? route('projects.ticket.detail', ['project' => $project->id, 'ticket' => $ticket->id]) : route('tickets.detail', $ticket->id) }}">{{ $ticket->subject }}</a></td>
                    @if(in_array('project', $display))<td><a href="{{ route('projects.detail', $ticket->project->id) }}">{{ $ticket->project->name }}</a></td>@endif
                    <td>@include('site.partials.user', ['user' => $ticket->reporter])</td>
                    <td>
                        @if($ticket->assignee)                                            
                            @include('site.partials.user', ['user' => $ticket->assignee])
                        @else
                            NaN
                        @endif
                    </td>
                    <td>{{ $ticket->created_at->format('d.m.Y') }}</td>
                    <td>@include('tickets.partials.status', ['status' => $ticket->status])</td>
                    <td>@include('tickets.partials.type', ['type' => $ticket->type])</td>
                    <td><span class="text-{{ $ticket->priority == App\Enums\TicketPriorityEnum::urgent ? 'danger font-weight-bold' : 'body' }}">@include('tickets.partials.priority', ['priority' => $ticket->priority])</span></td>
                    <td><span class="text-{{ $ticket->overdue ? 'danger' : 'body' }}">{{ $ticket->due_date->format('d.m.Y') }}</span></td>
                    <td>
                        <a href="{{ $redirect == 'project' ? route('projects.ticket.edit', ['project' => $project->id, 'ticket' => $ticket->id]) : route('tickets.edit', $ticket->id) }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ $redirect == 'project' ? route('projects.ticket.detail', ['project' => $project->id, 'ticket' => $ticket->id]) : route('tickets.detail', $ticket->id) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                        @include('tickets.partials.buttons', ['ticket' => $ticket, 'buttonSize' => 'xs', 'buttonText' => false])               
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
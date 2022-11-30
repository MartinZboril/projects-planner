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
                    <td><span class="text-{{ $ticket->priority == 4 ? 'danger font-weight-bold' : 'body' }}">@include('tickets.partials.priority', ['priority' => $ticket->priority])</span></td>
                    <td><span class="text-{{ $ticket->overdue ? 'danger' : 'body' }}">{{ $ticket->due_date->format('d.m.Y') }}</span></td>
                    <td>
                        <a href="{{ $redirect == 'project' ? route('projects.ticket.edit', ['project' => $project->id, 'ticket' => $ticket->id]) : route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ $redirect == 'project' ? route('projects.ticket.detail', ['project' => $project->id, 'ticket' => $ticket->id]) : route('tickets.detail', $ticket->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        @if (!$ticket->is_convert && $ticket->assignee_id && $ticket->status != 2 && $ticket->status != 3)
                            <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('convert-ticket-{{ $ticket->id }}-to-task-form').submit();"><i class="fas fa-tasks mr-1"></i>Convert to task</a>
                        @endif
                        @if ($ticket->status == 1)
                            <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('close-ticket-{{ $ticket->id }}-form').submit();"><i class="fas fa-check mr-1"></i>Close</a>
                        @elseif ($ticket->status == 2 || $ticket->status == 3)
                            <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('open-ticket-{{ $ticket->id }}-form').submit();"><i class="fas fa-bell mr-1"></i>Open</a>
                        @endif
                        @if ($ticket->status != 2 && $ticket->status != 3)
                            <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('archive-ticket-{{ $ticket->id }}-form').submit();"><i class="fas fa-archive"></i></a>
                        @endif
                        <!-- Tickets forms -->
                        @include('tickets.partials.forms', ['ticket' => $ticket])                
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
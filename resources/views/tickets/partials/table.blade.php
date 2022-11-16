<div class="table-responsive">
    <table id="@if($tickets->count() > 0){{ 'tickets-table' }}@endif" class="table table-bordered table-striped">
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
            </tr>
        </thead>
        <tbody>
            @forelse ($tickets as $ticket)
                <tr>
                    <td><a href="{{ route('tickets.detail', $ticket->id) }}">{{ $ticket->subject }}</a></td>
                    @if(in_array('project', $display))<td>{{ $ticket->project->name }}</td>@endif
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
                    <td>@include('tickets.partials.priority', ['priority' => $ticket->priority])</td>
                    <td>{{ $ticket->due_date->format('d.m.Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No tickets were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>  
</div>
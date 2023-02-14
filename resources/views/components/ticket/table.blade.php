<div class="table-responsive">
    <table id="{{ $tickets->count() == 0 ?: $tableId }}" class="table table-bordered table-striped">
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
                    <td><a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->subject }}</a></td>
                    <td><a href="{{ route('projects.show', $ticket->project) }}">{{ $ticket->project->name }}</a></td>
                    <td><x-site.ui.user-icon :user="$ticket->reporter" /></td>
                    <td><x-site.ui.user-icon :user="$ticket->assignee ?? null" /></td>
                    <td>{{ $ticket->created_at->format('d.m.Y') }}</td>
                    <td><x-ticket.ui.status-badge :text="true" :status="$ticket->status" /></td>
                    <td><x-ticket.ui.type :type="$ticket->type" /></td>
                    <td><span class="text-{{ $ticket->urgent ? 'danger font-weight-bold' : 'body' }}"><x-ticket.ui.priority :priority="$ticket->priority" /></span></td>
                    <td><span class="text-{{ $ticket->overdue ? 'danger' : 'body' }}">{{ $ticket->due_date->format('d.m.Y') }}</span></td>
                    <td>
                        <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                        @include('tickets.partials.buttons', ['buttonSize' => 'xs', 'hideButtonText' => ''])               
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

@push('scripts')
    <script>
        $(function () {
            $("#{{ $tableId }}").DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
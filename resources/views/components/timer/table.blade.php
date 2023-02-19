<div class="table-responsive">
    <table id="{{ $timers->count() === 0 ?: $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                @if ($type === 'timers')
                    <th>Project</th>                    
                @endif
                <th>Type</th>
                <th>User</th>
                <th>Total time (Hours)</th>
                <th>Amount</th>
                <th>Start</th>
                <th>Stop</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($timers as $timer)
                <tr>
                    <th>
                        @if ($timer->note)
                            <i class="fas fa-info-circle" data-toggle="popover" title="Note" data-content="{{ $timer->note }}"></i>
                        @else
                            #
                        @endif
                    </th>
                    @if ($type === 'timers')
                        <td><a href="{{ route('projects.show', $timer->project) }}">{{ $timer->project->name }}</a></td>                        
                    @endif                    
                    <td><a href="{{ route('users.rates.edit', ['user' => $timer->user, 'rate' => $timer->rate]) }}">{{ $timer->rate->name }}</a></td>
                    <td><x-site.ui.user-icon :user="$timer->user" /></td>
                    <td>{{ $timer->until ? $timer->total_time : 'N/A' }}</td>
                    <td>
                        @if ($timer->until)
                            @money($timer->amount)
                        @else
                            N/A
                        @endif  
                    </td>
                    <td>{{ $timer->since->format('d.m.Y H:i') }}</td>
                    <td>{{ $timer->until ? $timer->until->format('d.m.Y H:i') : 'N/A' }}</td>
                    <td>{{ $timer->since->format('d.m.Y') }}</td>
                    <td>
                        @if($timer->edit_route)
                            <a href="{{ $timer->edit_route }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No timesheets were found!</td>
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
            $('[data-toggle="popover"]').popover();
        });
    </script>
@endpush
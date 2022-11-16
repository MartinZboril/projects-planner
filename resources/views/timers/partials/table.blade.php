<div class="table-responsive">
    <table id="@if($timers->count() > 0){{ 'timesheets-table' }}@endif" class="table table-bordered table-striped">
        <thead>
            <tr>
                @if(in_array('project', $display))<th>Project</th>@endif
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
                    @if(in_array('project', $display))<td>{{ $timer->project->name }}</td>@endif
                    <td>{{ $timer->rate->name }}</td>
                    <td>{{ $timer->user->full_name }}</td>
                    <td>{{ $timer->until ? $timer->total_time : 'N/A' }}</td>
                    <td>{{ $timer->until ? $timer->amount : 'N/A' }}</td>
                    <td>{{ $timer->since->format('d.m.Y H:i') }}</td>
                    <td>{{ $timer->until ? $timer->until->format('d.m.Y H:i') : 'N/A' }}</td>
                    <td>{{ $timer->since->format('d.m.Y') }}</td>
                    <td>
                        @if($timer->until)
                            <a href="{{ route('timers.edit', ['project' => $timer->project->id, 'timer' => $timer->id]) }}" class="btn btn-sm btn-dark" href=""><i class="fas fa-pencil-alt"></i></a>
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
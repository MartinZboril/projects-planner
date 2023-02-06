<div class="table-responsive">
    <table id="@if($timers->count() > 0){{ 'timesheets-table' }}@endif" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
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
                    <th>
                        @if ($timer->note)
                            <i class="fas fa-info-circle" data-toggle="popover" title="Note" data-content="{{ $timer->note }}"></i>
                        @else
                            #
                        @endif
                    </th>
                    @if(in_array('project', $display))<td><a href="{{ route('projects.detail', $timer->project->id) }}">{{ $timer->project->name }}</a></td>@endif
                    <td><a href="{{ route('users.rates.edit', ['user' => $timer->user->id, 'rate' => $timer->rate->id]) }}">{{ $timer->rate->name }}</a></td>
                    <td>@include('site.partials.user', ['user' => $timer->user])</td>
                    <td>{{ $timer->until ? $timer->total_time : 'N/A' }}</td>
                    <td>
                        @if ($timer->until)
                            @include('site.partials.amount', ['value' => $timer->amount])
                        @else
                            N/A
                        @endif  
                    </td>
                    <td>{{ $timer->since->format('d.m.Y H:i') }}</td>
                    <td>{{ $timer->until ? $timer->until->format('d.m.Y H:i') : 'N/A' }}</td>
                    <td>{{ $timer->since->format('d.m.Y') }}</td>
                    <td>
                        @if($timer->until)
                            <a href="{{ route('projects.timers.edit', ['project' => $timer->project->id, 'timer' => $timer->id]) }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
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
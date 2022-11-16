<div class="table-responsive">
    <table id="@if($projects->count() > 0){{ 'projects-table' }}@endif" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Client</th>
                <th>Status</th>
                <th>Team</th>
                <th>Plan</th>
                <th>Total Time</th>
                <th>Budget</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($projects as $project)
                <tr>
                    <td><a href="{{ route('projects.detail', $project->id) }}">{{ $project->name }}</a></td>
                    <td>{{ $project->client->name }}</td>
                    <td>@include('projects.partials.status', ['status' => $project->status])</td>
                    <td>
                        @foreach ($project->team as $user)
                            @include('site.partials.user', ['user' => $user])
                        @endforeach
                    </td>
                    <td>{{ $project->time_plan }} %</td>
                    <td>{{ $project->total_time }} Hours</td>
                    <td>{{ $project->budget_plan }} %</td>
                    <td>{{ number_format($project->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No projects were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
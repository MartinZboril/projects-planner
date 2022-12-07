<div class="table-responsive">
    <table id="@if($projects->count() > 0){{ 'projects-table' }}@endif" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Client</th>
                <th>Status</th>
                <th>Team</th>
                <th>Due date</th>
                <th>Plan</th>
                <th>Total Time</th>
                <th>Budget</th>
                <th>Amount</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($projects as $project)
                <tr>
                    <td><a href="{{ route('projects.detail', $project->id) }}">{{ $project->name }}</a></td>
                    <td><a href="{{ route('clients.detail', $project->client->id) }}">{{ $project->client->name }}</a></td>
                    <td>@include('projects.partials.status', ['status' => $project->status])</td>
                    <td>
                        @foreach ($project->team as $user)
                            @include('site.partials.user', ['user' => $user])
                        @endforeach
                    </td>
                    <td><span class="text-{{ $project->overdue ? 'danger' : 'body' }}">{{ $project->due_date->format('d.m.Y') }}</span></td>
                    <td><span class="text-{{ $project->time_plan > 100 ? 'danger' : 'body' }}">{{ $project->time_plan }} %</span></td>
                    <td>{{ $project->total_time }} Hours</td>
                    <td><span class="text-{{ $project->budget_plan > 100 ? 'danger' : 'body' }}">{{ $project->budget_plan }} %</span></td>
                    <td>@include('site.partials.amount', ['value' => $project->amount])</td>
                    <td>
                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ route('projects.detail', $project->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        @include('projects.partials.buttons', ['project' => $project])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No projects were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
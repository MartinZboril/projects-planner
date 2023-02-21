<div class="table-responsive">
    <table id="{{ $projects->count() === 0 ?: $tableId }}" class="table table-bordered table-striped">
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
                    <td><a href="{{ $project->show_route }}">{{ $project->name }}</a></td>
                    <td><a href="{{ $project->client_show_route }}">{{ $project->client->name }}</a></td>
                    <td><x-project.ui.status-badge :text="true" :status="$project->status" /></td>
                    <td>
                        @foreach ($project->team as $user)
                            <x-site.ui.user-icon :$user />
                        @endforeach
                    </td>
                    <td><span class="text-{{ $project->overdue ? 'danger' : 'body' }}">{{ $project->due_date->format('d.m.Y') }}</span></td>
                    <td><span class="text-{{ $project->time_plan > 100 ? 'danger' : 'body' }}">{{ $project->time_plan }} %</span></td>
                    <td>{{ $project->total_time }} Hours</td>
                    <td><span class="text-{{ $project->budget_plan > 100 ? 'danger' : 'body' }}">{{ $project->budget_plan }} %</span></td>
                    <td>@money($project->amount)</td>
                    <td>
                        <a href="{{ $project->edit_route }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ $project->show_route }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                        @include('projects.partials.buttons', ['buttonSize' => 'xs', 'hideButtonText' => ''])
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

@push('scripts')
    <script>
        $(function () {
            $("#{{ $tableId }}").DataTable();
        });
    </script>
@endpush
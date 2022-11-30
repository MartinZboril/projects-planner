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
                        @if ($project->status == 1)
                            <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('finish-project-{{ $project->id }}-form').submit();"><i class="fas fa-check mr-1"></i>Finish</a>
                        @elseif ($project->status == 2 || $project->status == 3)
                            <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('active-project-{{ $project->id }}-form').submit();"><i class="fas fa-cogs mr-1"></i>Active</a>
                        @endif
                        @if ($project->status != 2 && $project->status != 3)
                            <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('archive-project-{{ $project->id }}-form').submit();"><i class="fas fa-archive"></i></a>
                        @endif
                        @if(Auth::User()->activeTimers->contains('project_id', $project->id))
                            <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-project-{{ $project->id }}').submit();"><i class="fas fa-stop mr-1"></i>Stop</a>
                        @else
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Start
                                </button>
                                <div class="dropdown-menu">
                                    @foreach (Auth::User()->rates as $rate)
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('start-working-on-project-{{ $project->id }}-with-rate-{{ $rate->id }}').submit();">{{ $rate->name }} ({{ $rate->value }})</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <!-- Projects forms -->
                        @include('projects.partials.forms', ['project' => $project])
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
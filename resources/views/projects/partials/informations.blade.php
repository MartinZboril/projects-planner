<div class="card card-primary card-outline">
    <div class="card-header">
        Project
        <x-project.ui.status-badge :text="true" :status="$project->status" />
        @if($project->overdue)<span class="badge badge-danger ml-1" style="font-size:14px;">Overdue</span>@endif
    </div>
    <div class="card-body">
        <div class="text-muted">
            <p class="text-sm">Name
                <b class="d-block ml-2">{{ $project->name }}</b>
            </p>
            <hr>
            <p class="text-sm">Client
                <b class="d-block ml-2"><a href="{{ route('clients.show', $project->client) }}">{{ $project->client->name }}</a></b>
            </p>
            <hr>
            <p class="text-sm">Information
                <span class="d-block ml-2">Start date: <b>{{ $project->start_date->format('d.m.Y') }}</b></span>
                <span class="d-block ml-2">Due date: <b>{{ $project->due_date->format('d.m.Y') }}</b></span>
                <span class="d-block ml-2">Deadline: <span class="badge badge-{{ $project->deadline >= 0 ? 'success' : 'danger' }}">{{ $project->deadline }} day(s)</span>
            </span>
            </p>
            <p class="text-sm">Cost
                <span class="d-block ml-2">Est. Hours: <b>{{ $project->estimated_hours }} Hours</b></span>
                <span class="d-block ml-2">Remaining Hours: <b><span class="text-{{ $project->remaining_hours >= 0 ? 'sm' : 'danger' }}">{{ $project->remaining_hours }} Hours</span></b><span class="badge badge-{{ $project->time_plan > 100 ? 'danger' : 'success' }} ml-1">{{ $project->time_plan }} %</span></span>
                <span class="d-block ml-2">Budget: <b>@money($project->budget)</b></span>
                <span class="d-block ml-2">Remaining Budget: <b><span class="text-{{ $project->remaining_budget >= 0 ? 'sm' : 'danger' }}">@money($project->remaining_budget)</span></b><span class="badge badge-{{ $project->budget_plan > 100 ? 'danger' : 'success' }} ml-1">{{ $project->budget_plan }} %</span></span>
            </p>                            
            <hr>
            <p class="text-sm">Team
                <ul class="list-group list-group-flush ml-1">
                    @foreach ($project->team as $user)
                        <li class="list-group-item">
                            <div class="user-block">
                                <x-site.ui.user-icon :$user />
                                <span class="username"><a href="{{ route('users.show', $user) }}">{{ $user->full_name }}</a></span>
                                <span class="description">Member</span>
                            </div> 
                        </li>
                    @endforeach
                </ul>
            </p>
            <hr>
            <p class="text-sm">Description
                <b class="d-block ml-2">{!! $project->description !!}</b>
            </p>
        </div>
    </div>
</div>
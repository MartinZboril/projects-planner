<div class="table-responsive">
    <table id="@if($milestones->count() > 0){{ 'milestones-table' }}@endif" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                @if(in_array('project', $display))<th>Project</th>@endif
                <th>Owner</th>
                <th>Progress</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($milestones as $milestone)
                <tr>                                        
                    <td><a href="{{ route('projects.milestones.detail', ['project' => $milestone->project->id, 'milestone' => $milestone->id]) }}">{{ $milestone->name }}</a></td>                                        
                    @if(in_array('project', $display))<td>{{ $milestone->project->name }}</td>@endif
                    <td>@include('site.partials.user', ['user' => $milestone->owner])</td>
                    <td>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="{{ $milestone->progress * 100 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $milestone->progress * 100 }}%"></div>
                        </div>
                        <small>{{ $milestone->progress * 100 }} % Complete ({{ $milestone->tasksCompleted->count() }}/{{ $milestone->tasks->count() }})</small>
                    </td>
                    <td>{{ $milestone->start_date->format('d.m.Y') }}</td>
                    <td><span class="text-{{ $milestone->overdue ? 'danger' : 'body' }}">{{ $milestone->end_date->format('d.m.Y') }}</span></td>
                    <td>
                        <a href="{{ route('projects.milestones.edit', ['project' => $milestone->project->id, 'milestone' => $milestone->id]) }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ route('projects.milestones.detail', ['project' => $milestone->project->id, 'milestone' => $milestone->id]) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No milestones were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>  
</div>  
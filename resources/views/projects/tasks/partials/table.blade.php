<div class="table-responsive">
    <table id="@if($tasks->count() > 0){{ $id }}@endif" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Project</th>
                <th>Milestone</th>
                <th>User</th>
                <th>Due Date</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                <tr>
                    <td><a href="{{ route('projects.tasks.show', ['project' => $project, 'task' => $task]) }}">{{ $task->name }}</a></td>
                    <td><a href="{{ route('projects.show', $task->project) }}">{{ $task->project->name }}</a></td>
                    <td>
                        @if ($task->milestone)
                            <a href="{{ route('projects.milestones.show', ['project' => $task->project, 'milestone' => $task->milestone]) }}">{{ $task->milestone->name }}</a></td>                            
                        @else
                            -
                        @endif
                    <td>@include('site.partials.user', ['user' => $task->user])</td>
                    <td><span class="text-{{ $task->overdue ? 'danger' : 'body' }}">{{ $task->due_date->format('d.m.Y') }}</span></td>
                    <td>@include('projects.tasks.partials.status', ['task' => $task])</td>
                    <td>
                        <a href="{{ route('projects.tasks.edit', ['project' => $project, 'task' => $task]) }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ route('projects.tasks.show', ['project' => $project, 'task' => $task]) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                        @include('projects.tasks.partials.buttons', ['project' => $project, 'task' => $task, 'buttonSize' => 'xs', 'buttonText' => false])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center">No tasks were found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>  
</div>
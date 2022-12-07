<div class="table-responsive">
    <table id="@if($tasks->count() > 0){{ $id }}@endif" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                @if(in_array('project', $display))<th>Project</th>@endif
                <th>User</th>
                <th>Due Date</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                <tr>
                    <td><a href="{{ $redirect == 'project' ? route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) : route('tasks.detail', $task->id) }}">{{ $task->name }}</a></td>
                    @if(in_array('project', $display))<td><a href="{{ route('projects.detail', $task->project->id) }}">{{ $task->project->name }}</a></td>@endif
                    <td>@include('site.partials.user', ['user' => $task->user])</td>
                    <td><span class="text-{{ $task->overdue ? 'danger' : 'body' }}">{{ $task->due_date->format('d.m.Y') }}</span></td>
                    <td>@include('tasks.partials.status', ['task' => $task])</td>
                    <td>
                        <a href="{{ $redirect == 'project' ? route('projects.task.edit', ['project' => $project->id, 'task' => $task->id]) : route('tasks.edit', $task->id) }}" class="btn btn-sm btn-dark"><i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ $redirect == 'project' ? route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) : route('tasks.detail', $task->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        @include('tasks.partials.buttons', ['task' => $task])
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
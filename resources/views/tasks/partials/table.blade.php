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
                        @if ($task->status == 1)
                            <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('start-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-play mr-1"></i>Start</a>
                        @elseif ($task->status == 2)
                            <a href="#" class="btn btn-sm btn-success {{ ($task->is_stopped) ? 'disabled' : '' }}" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-check mr-1"></i>Complete</a>
                            @if ($task->is_stopped)
                                <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-hourglass-start mr-1"></i>Resume</a>
                            @else
                                <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-stop mr-1"></i>Stop</a>
                            @endif
                        @else
                            <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('return-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-undo mr-1"></i>Return</a>
                        @endif
                        <!-- Tasks forms -->
                        @include('tasks.partials.forms', ['task' => $task])
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
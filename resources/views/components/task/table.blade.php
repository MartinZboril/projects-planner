<div class="table-responsive">
    <table id="{{ $tasks->count() == 0 ?: $tableId }}" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                @if ($type === 'tasks')
                    <th>Project</th>
                @endif
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
                    <td><a href="{{ $type === 'projects' ? route('projects.tasks.show', ['project' => $task->project, 'task' => $task]) : route('tasks.show', $task) }}">{{ $task->name }}</a></td>
                    @if ($type === 'tasks')
                        <td><a href="{{ route('projects.show', $task->project) }}">{{ $task->project->name }}</a></td>
                    @endif
                    <td>
                        @if ($task->milestone)
                            <a href="{{ route('projects.milestones.show', ['project' => $task->project, 'milestone' => $task->milestone]) }}">{{ $task->milestone->name }}</a></td>                            
                        @else
                            NaN
                        @endif
                    <td><x-site.ui.user-icon :user="$task->user" /></td>
                    <td><span class="text-{{ $task->overdue ? 'danger' : 'body' }}">{{ $task->due_date->format('d.m.Y') }}</span></td>
                    <td><x-task.ui.status-badge :text="true" :$task /></td>
                    <td>
                        @if ($type === 'projects')
                            <a href="{{ route('projects.tasks.edit', ['project' => $task->project, 'task' => $task]) }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                            <a href="{{ route('projects.tasks.show', ['project' => $task->project, 'task' => $task]) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                            @include('projects.tasks.partials.buttons', ['project' => $task->project, 'task' => $task, 'buttonSize' => 'xs', 'hideButtonText' => ''])
                        @else
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-xs btn-dark"><i class="fas fa-pencil-alt"></i></a>
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                            @include('tasks.partials.buttons', ['task' => $task, 'buttonSize' => 'xs', 'hideButtonText' => ''])
                        @endif
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

@push('scripts')
    <script>
        $(function () {
            $('#{{ $tableId }}').DataTable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
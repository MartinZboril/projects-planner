<div class="card-title">
    <a href="{{ route('projects.tasks.show', ['project' => $task->project->id, 'task' => $task->id]) }}">{{ $task->name }}</a>
    @switch($task->status)
        @case(App\Enums\TaskStatusEnum::new)
            <a href="#" class="btn btn-sm btn-tool" onclick="changeTaskStatus('{{ route('tasks.change_status', $task) }}', {{ App\Enums\TaskStatusEnum::in_progress }})"><i class="fas fa-play" data-toggle="tooltip" data-placement="bottom" title="Start"></i></a>
            @break
    
        @case(App\Enums\TaskStatusEnum::in_progress)
            @if($task->paused || $task->returned)
                <x-task.ui.status-badge :text="false" :$task />
            @endif
            @if($task->overdue)<span class="badge badge-danger ml-1" style="font-size:14px;">Overdue</span>@endif            
            @break
            
    @endswitch
</div>
<div class="card-tools">
    @switch($task->status)
        @case(App\Enums\TaskStatusEnum::new)
            @if($task->overdue)<span class="badge badge-danger ml-2" style="font-size:14px;">Overdue</span>@endif
            @break
    
        @case(App\Enums\TaskStatusEnum::in_progress)
            @if ($task->paused)
                <a href="#" class="btn btn-sm btn-tool" onclick="pauseTask('{{ route('tasks.pause', $task) }}')"><i class="fas fa-hourglass-start" data-toggle="tooltip" data-placement="bottom" title="Resume"></i></a>
            @else
                <a href="#" class="btn btn-sm btn-tool" onclick="changeTaskStatus('{{ route('tasks.change_status', $task) }}', {{ App\Enums\TaskStatusEnum::complete }})"><i class="fas fa-check" data-toggle="tooltip" data-placement="bottom" title="Complete"></i></a>
                <a href="#" class="btn btn-sm btn-tool" onclick="pauseTask('{{ route('tasks.pause', $task) }}')"><i class="fas fa-stop" data-toggle="tooltip" data-placement="bottom" title="Stop"></i></a>
            @endif
            @break
            
        @case(App\Enums\TaskStatusEnum::complete)
            <a href="#" class="btn btn-sm btn-tool" onclick="changeTaskStatus('{{ route('tasks.change_status', $task) }}', {{ App\Enums\TaskStatusEnum::new }})"><i class="fas fa-undo" data-toggle="tooltip" data-placement="bottom" title="Return"></i></a>
            @break
                      
    @endswitch
</div>
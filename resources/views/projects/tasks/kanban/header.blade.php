<div class="card-title">
    <a href="{{ route('projects.tasks.show', ['project' => $project->id, 'task' => $task->id]) }}">{{ $task->name }}</a>
    @switch($task->status)
        @case(App\Enums\TaskStatusEnum::new)
            <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('start-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-play" data-toggle="tooltip" data-placement="bottom" title="Start"></i></a>
            @break
    
        @case(App\Enums\TaskStatusEnum::in_progress)
            @if($task->paused)
                <span class="badge badge-@include('projects.tasks.partials.colour', ['task' => $task]) ml-2" style='font-size:14px;'>@include('projects.tasks.partials.status', ['task' => $task])</span>
            @elseif($task->returned)
                <span class="badge badge-@include('projects.tasks.partials.colour', ['task' => $task]) ml-2" style='font-size:14px;'>@include('projects.tasks.partials.status', ['task' => $task])</span>
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
                <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-hourglass-start" data-toggle="tooltip" data-placement="bottom" title="Resume"></i></a>
            @else
                <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-check" data-toggle="tooltip" data-placement="bottom" title="Complete"></i></a>
                <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-stop" data-toggle="tooltip" data-placement="bottom" title="Stop"></i></a>
            @endif
            @break
            
        @case(App\Enums\TaskStatusEnum::complete)
            <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('return-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-undo" data-toggle="tooltip" data-placement="bottom" title="Return"></i></a>
            @break
                      
    @endswitch
</div>
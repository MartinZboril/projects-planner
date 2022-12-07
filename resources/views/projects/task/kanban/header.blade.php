<div class="card-title">
    <a href="{{ route('projects.task.detail', ['project' => $project->id, 'task' => $task->id]) }}">{{ $task->name }}</a>
    @switch($task->status)
        @case(1)
            <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('start-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-play" data-toggle="tooltip" data-placement="bottom" title="Start"></i></a>
            @break
    
        @case(2)
            @if($task->is_stopped)
                <span class="badge badge-@include('tasks.partials.colour', ['task' => $task]) ml-2" style='font-size:14px;'>@include('tasks.partials.status', ['task' => $task])</span>
            @elseif($task->is_returned)
                <span class="badge badge-@include('tasks.partials.colour', ['task' => $task]) ml-2" style='font-size:14px;'>@include('tasks.partials.status', ['task' => $task])</span>
            @endif
            @if($task->overdue)<span class="badge badge-danger ml-1" style="font-size:14px;">Overdue</span>@endif            
            @break
            
    @endswitch
</div>
<div class="card-tools">
    @switch($task->status)
        @case(1)
            @if($task->overdue)<span class="badge badge-danger ml-2" style="font-size:14px;">Overdue</span>@endif
            @break
    
        @case(2)
            @if ($task->is_stopped)
                <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-hourglass-start" data-toggle="tooltip" data-placement="bottom" title="Resume"></i></a>
            @else
                <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-check" data-toggle="tooltip" data-placement="bottom" title="Complete"></i></a>
                <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-stop" data-toggle="tooltip" data-placement="bottom" title="Stop"></i></a>
            @endif
            @break
            
        @case(3)
            <a href="#" class="btn btn-sm btn-tool" onclick="event.preventDefault(); document.getElementById('return-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-undo" data-toggle="tooltip" data-placement="bottom" title="Return"></i></a>
            @break
                      
    @endswitch
</div>
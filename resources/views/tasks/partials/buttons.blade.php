@if ($task->status == App\Enums\TaskStatusEnum::new)
    <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('start-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-play mr-1"></i>Start</a>
@elseif ($task->status == App\Enums\TaskStatusEnum::in_progress)
    <a href="#" class="btn btn-sm btn-success {{ ($task->paused) ? 'disabled' : '' }}" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-check mr-1"></i>Complete</a>
    @if ($task->paused)
        <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-hourglass-start mr-1"></i>Resume</a>
    @else
        <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-stop mr-1"></i>Stop</a>
    @endif
@else
    <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('return-working-on-task-{{ $task->id }}-form').submit();"><i class="fas fa-undo mr-1"></i>Return</a>
@endif
<!-- Tasks forms -->
@include('tasks.partials.forms', ['task' => $task])
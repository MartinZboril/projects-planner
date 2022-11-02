@if ($task->status == 1)
    <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('start-working-on-task-form').submit();"><i class="fas fa-play mr-1"></i> Start</a>
@elseif ($task->status == 2)
    <a href="#" class="btn btn-sm btn-success {{ ($task->is_stopped) ? 'disabled' : '' }}" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-form').submit();"><i class="fas fa-check mr-1"></i> Complete</a>
    @if ($task->is_stopped)
        <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-form').submit();"><i class="fas fa-hourglass-start mr-1"></i> Resume</a>
    @else
        <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-form').submit();"><i class="fas fa-stop mr-1"></i> Stop</a>
    @endif
@else
    <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('return-working-on-task-form').submit();"><i class="fas fa-undo mr-1"></i> Return</a>
@endif
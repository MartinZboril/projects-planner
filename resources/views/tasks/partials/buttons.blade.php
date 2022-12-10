@if ($task->status == App\Enums\TaskStatusEnum::new)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-info" onclick="event.preventDefault(); document.getElementById('start-working-on-task-{{ $task->id }}-form').submit();">@include('site.partials.icon', ['icon' => 'fas fa-play', 'text' => $buttonText ? 'Start' : ''])</a>
@elseif ($task->status == App\Enums\TaskStatusEnum::in_progress)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-success {{ ($task->paused) ? 'disabled' : '' }}" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-{{ $task->id }}-form').submit();">@include('site.partials.icon', ['icon' => 'fas fa-check', 'text' => $buttonText ? 'Complete' : ''])</a>
    @if ($task->paused)
        <a href="#" class="btn btn-{{ $buttonSize }} btn-info" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-{{ $task->id }}-form').submit();">@include('site.partials.icon', ['icon' => 'fas fa-hourglass-start', 'text' => $buttonText ? 'Resume' : ''])</a>
    @else
        <a href="#" class="btn btn-{{ $buttonSize }} btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-{{ $task->id }}-form').submit();">@include('site.partials.icon', ['icon' => 'fas fa-stop', 'text' => $buttonText ? 'Stop' : ''])</a>
    @endif
@else
    <a href="#" class="btn btn-{{ $buttonSize }} btn-danger" onclick="event.preventDefault(); document.getElementById('return-working-on-task-{{ $task->id }}-form').submit();">@include('site.partials.icon', ['icon' => 'fas fa-undo', 'text' => $buttonText ? 'Return' : ''])</a>
@endif
<!-- Tasks forms -->
@include('tasks.partials.forms', ['task' => $task])
@if ($task->status == App\Enums\TaskStatusEnum::new)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-info" onclick="event.preventDefault(); document.getElementById('start-working-on-task-{{ $task->id }}-form').submit();"><x-site.ui.icon icon="fas fa-play" :text="$hideButtonText ?? 'Start'" /></a>
@elseif ($task->status == App\Enums\TaskStatusEnum::in_progress)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-success {{ !$task->paused ?: 'disabled' }}" onclick="event.preventDefault(); document.getElementById('complete-working-on-task-{{ $task->id }}-form').submit();"><x-site.ui.icon icon="fas fa-check" :text="$hideButtonText ?? 'Complete'" /></a>
    @if ($task->paused)
        <a href="#" class="btn btn-{{ $buttonSize }} btn-info" onclick="event.preventDefault(); document.getElementById('resume-working-on-task-{{ $task->id }}-form').submit();"><x-site.ui.icon icon="fas fa-hourglass-start" :text="$hideButtonText ?? 'Resume'" /></a>
    @else
        <a href="#" class="btn btn-{{ $buttonSize }} btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-task-{{ $task->id }}-form').submit();"><x-site.ui.icon icon="fas fa-stop" :text="$hideButtonText ?? 'Stop'" /></a>
        @endif
@else
    <a href="#" class="btn btn-{{ $buttonSize }} btn-danger" onclick="event.preventDefault(); document.getElementById('return-working-on-task-{{ $task->id }}-form').submit();"><x-site.ui.icon icon="fas fa-undo" :text="$hideButtonText ?? 'Return'" /></a>
@endif
<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="event.preventDefault(); document.getElementById('{{ ($task->is_marked ? 'unmark' : 'mark') . '-task-' . $task->id . '-form'}}').submit();">
    <i class="{{ ($task->is_marked ? 'fas' : 'far') }} fa-bookmark"></i>
</a>
<!-- Tasks forms -->
@include('tasks.partials.forms')
@include('tasks.forms.mark', ['id' => ($task->is_marked ? 'unmark' : 'mark') . '-task-' . $task->id . '-form'])
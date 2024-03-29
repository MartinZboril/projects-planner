<a href="#" style="{{ $task->status === App\Enums\TaskStatusEnum::new ? '' : 'display: none;' }}" class="btn btn-{{ $buttonSize }} btn-info" id="task-{{ $task->id }}-new-status" onclick="changeTaskStatus('{{ route('tasks.change_status', $task) }}', {{ App\Enums\TaskStatusEnum::in_progress }}, '{{ $type }}', '{{ __('pages.content.tasks.statuses.in_progress') }}', 'warning', '{{ $tableIdentifier }}')">
    <x-site.ui.icon icon="fas fa-play" :text="$hideButtonText ?? 'Start'" />
</a>
<a href="#" style="{{ $task->status === App\Enums\TaskStatusEnum::in_progress ? '' : 'display: none;' }}" class="btn btn-{{ $buttonSize }} btn-success {{ !$task->paused ?: 'disabled' }}" id="task-{{ $task->id }}-complete-status" onclick="changeTaskStatus('{{ route('tasks.change_status', $task) }}', {{ App\Enums\TaskStatusEnum::complete }}, '{{ $type }}', '{{ __('pages.content.tasks.statuses.complete') }}', 'success', '{{ $tableIdentifier }}')">
    <x-site.ui.icon icon="fas fa-check" :text="$hideButtonText ?? 'Complete'" />
</a>
<a href="#" style="{{ $task->status === App\Enums\TaskStatusEnum::complete ? '' : 'display: none;' }}" class="btn btn-{{ $buttonSize }} btn-danger" id="task-{{ $task->id }}-return-status" onclick="changeTaskStatus('{{ route('tasks.change_status', $task) }}', {{ App\Enums\TaskStatusEnum::new }}, '{{ $type }}', '{{ __('pages.content.tasks.statuses.return') }}', 'danger', '{{ $tableIdentifier }}')">
    <x-site.ui.icon icon="fas fa-undo" :text="$hideButtonText ?? 'Return'" />
</a>
<span style="{{ $task->status === App\Enums\TaskStatusEnum::in_progress ? '' : 'display: none;' }}" id="task-{{ $task->id }}-pause-div">
    <a href="#" style="{{ $task->paused ? '' : 'display: none;' }}" class="btn btn-{{ $buttonSize }} btn-info" id="task-{{ $task->id }}-resume" onclick="pauseTask('{{ route('tasks.pause', $task) }}', '{{ $type }}', '{{ __('pages.content.tasks.statuses.in_progress') }}', 'warning', '{{ $tableIdentifier }}')">
        <x-site.ui.icon icon="fas fa-hourglass-start" :text="$hideButtonText ?? 'Resume'" />
    </a>
    <a href="#" style="{{ !$task->paused ? '' : 'display: none;' }}" class="btn btn-{{ $buttonSize }} btn-danger" id="task-{{ $task->id }}-stop" onclick="pauseTask('{{ route('tasks.pause', $task) }}', '{{ $type }}', '{{ __('pages.content.tasks.statuses.stop') }}', 'danger', '{{ $tableIdentifier }}')">
        <x-site.ui.icon icon="fas fa-stop" :text="$hideButtonText ?? 'Stop'" />
    </a>
</span>
<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="markTask('{{ route('tasks.mark', $task) }}', '{{ $tableIdentifier }}')">
    <i class="{{ ($task->is_marked ? 'fas' : 'far') }} fa-bookmark" id="task-{{ $task->id }}-marked"></i>
</a>
<a href="#" class="btn btn-{{ $buttonSize }} btn-danger" onclick="deleteTask('{{ route('tasks.destroy', $task) }}', '{{ $type }}', '{{ $tableIdentifier }}', '{{ $redirect }}')">
    <x-site.ui.icon icon="fas fa-trash" :text="$hideButtonText ?? 'Delete'" />
</a>
@if($task->paused)
    {{ 'danger' }}
@elseif($task->returned)
    {{ 'danger' }}
@else
    @switch($task->status)
        @case(App\Enums\TaskStatusEnum::new)
            {{ 'info' }}
            @break
            
        @case(App\Enums\TaskStatusEnum::in_progress)
            {{ 'warning' }}
            @break

        @case(App\Enums\TaskStatusEnum::complete)
            {{ 'success' }}
            @break

        @default
            {{ 'info' }}
    @endswitch
@endif
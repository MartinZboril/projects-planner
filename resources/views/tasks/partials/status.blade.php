@if($task->paused)
    {{ __('pages.content.tasks.statuses.stop') }}
@elseif($task->returned)
    {{ __('pages.content.tasks.statuses.return') }}
@else
    @switch($task->status)
        @case(App\Enums\TaskStatusEnum::new)
            {{ __('pages.content.tasks.statuses.new') }}
            @break
            
        @case(App\Enums\TaskStatusEnum::in_progress)
            {{ __('pages.content.tasks.statuses.in_progress') }}
            @break

        @case(App\Enums\TaskStatusEnum::complete)
            {{ __('pages.content.tasks.statuses.complete') }}
            @break

        @default
            -

    @endswitch
@endif
@if($task->is_stopped)
    {{ __('pages.content.tasks.statuses.stop') }}
@elseif($task->is_returned)
    {{ __('pages.content.tasks.statuses.return') }}
@else
    @switch($task->status)
        @case(1)
            {{ __('pages.content.tasks.statuses.new') }}
            @break
            
        @case(2)
            {{ __('pages.content.tasks.statuses.in_progress') }}
            @break

        @case(3)
            {{ __('pages.content.tasks.statuses.complete') }}
            @break

        @default
            -
    @endswitch
@endif
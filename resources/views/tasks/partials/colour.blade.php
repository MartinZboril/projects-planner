@if($task->is_stopped)
    {{ 'danger' }}
@elseif($task->is_returned)
    {{ 'danger' }}
@else
    @switch($task->status)
        @case(1)
            {{ 'info' }}
            @break
            
        @case(2)
            {{ 'warning' }}
            @break

        @case(3)
            {{ 'success' }}
            @break

        @default
            {{ 'info' }}
    @endswitch
@endif
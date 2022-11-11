@switch($status)
    @case(1)
        {{ __('pages.content.projects.statuses.active') }}
        @break
        
    @case(2)
        {{ __('pages.content.projects.statuses.finish') }}
        @break

    @case(3)
        {{ __('pages.content.projects.statuses.archive') }}
        @break

    @default
        -
@endswitch
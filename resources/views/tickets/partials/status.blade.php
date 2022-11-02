@switch($status)
    @case(1)
        {{ __('pages.content.tickets.statuses.open') }}
        @break
        
    @case(2)
        {{ __('pages.content.tickets.statuses.close') }}
        @break

    @case(3)
        {{ __('pages.content.tickets.statuses.archive') }}
        @break

    @default
        -
@endswitch
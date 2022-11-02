@switch($priority)
    @case(1)
        {{ __('pages.content.tickets.priorities.low') }}
        @break
        
    @case(2)
        {{ __('pages.content.tickets.priorities.medium') }}
        @break

    @case(3)
        {{ __('pages.content.tickets.priorities.high') }}
        @break

    @case(4)
        {{ __('pages.content.tickets.priorities.urgent') }}
        @break

    @default
        -
@endswitch
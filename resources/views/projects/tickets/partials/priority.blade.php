@switch($priority)
    @case(App\Enums\TicketPriorityEnum::low)
        {{ __('pages.content.tickets.priorities.low') }}
        @break
        
    @case(App\Enums\TicketPriorityEnum::medium)
        {{ __('pages.content.tickets.priorities.medium') }}
        @break

    @case(App\Enums\TicketPriorityEnum::high)
        {{ __('pages.content.tickets.priorities.high') }}
        @break

    @case(App\Enums\TicketPriorityEnum::urgent)
        {{ __('pages.content.tickets.priorities.urgent') }}
        @break

    @default
        -
@endswitch
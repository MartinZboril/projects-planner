@switch($status)
    @case(App\Enums\TicketStatusEnum::open)
        {{ __('pages.content.tickets.statuses.open') }}
        @break
        
    @case(App\Enums\TicketStatusEnum::close)
        {{ __('pages.content.tickets.statuses.close') }}
        @break

    @case(App\Enums\TicketStatusEnum::archive)
        {{ __('pages.content.tickets.statuses.archive') }}
        @break

    @default
        -

@endswitch
@switch($status)
    @case(App\Enums\TicketStatusEnum::open)
        {{ 'info' }}
        @break
        
    @case(App\Enums\TicketStatusEnum::close)
        {{ 'success' }}
        @break

    @case(App\Enums\TicketStatusEnum::archive)
        {{ 'primary' }}
        @break

    @default
        -
@endswitch
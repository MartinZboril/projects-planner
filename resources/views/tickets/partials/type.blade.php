@switch($type)
    @case(App\Enums\TicketTypeEnum::error)
        {{ __('pages.content.tickets.types.error') }}
        @break
 
    @case(App\Enums\TicketTypeEnum::inovation)
        {{ __('pages.content.tickets.types.inovation') }}
        @break
 
    @case(App\Enums\TicketTypeEnum::help)
        {{ __('pages.content.tickets.types.help') }}
        @break
    
    @case(App\Enums\TicketTypeEnum::other)
        {{ __('pages.content.tickets.types.other') }}
        @break

    @default
        -
        
@endswitch
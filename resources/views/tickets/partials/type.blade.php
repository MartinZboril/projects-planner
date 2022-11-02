@switch($type)
    @case(1)
        {{ __('pages.content.tickets.types.error') }}
        @break
 
    @case(2)
        {{ __('pages.content.tickets.types.inovation') }}
        @break
 
    @case(3)
        {{ __('pages.content.tickets.types.help') }}
        @break
    
    @case(4)
        {{ __('pages.content.tickets.types.other') }}
        @break

    @default
        -
@endswitch
@switch($status)
    @case(1)
        {{ 'info' }}
        @break
        
    @case(2)
        {{ 'success' }}
        @break

    @case(3)
        {{ 'primary' }}
        @break

    @default
        -
@endswitch
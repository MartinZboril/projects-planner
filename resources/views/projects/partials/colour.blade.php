@switch($status)
    @case(App\Enums\ProjectStatusEnum::active)
        {{ 'info' }}
        @break
        
    @case(App\Enums\ProjectStatusEnum::finish)
        {{ 'success' }}
        @break

    @case(App\Enums\ProjectStatusEnum::archive)
        {{ 'primary' }}
        @break

    @default
        -
@endswitch
@switch($status)
    @case(App\Enums\ProjectStatusEnum::active)
        {{ __('pages.content.projects.statuses.active') }}
        @break
        
    @case(App\Enums\ProjectStatusEnum::finish)
        {{ __('pages.content.projects.statuses.finish') }}
        @break

    @case(App\Enums\ProjectStatusEnum::archive)
        {{ __('pages.content.projects.statuses.archive') }}
        @break

    @default
        -

@endswitch
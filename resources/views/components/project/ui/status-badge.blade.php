@if ($text)
    {{ $title }}
@else
    <span class="badge badge-{{ $background }} ml-2" style='font-size:14px;'>
        {{ $title }}
    </span>
@endif

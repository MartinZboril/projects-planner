<div class="card">
    <div class="card-body">
        <b>{{ $title }}</b> <a class="float-right {{ $colour }}">
            @if (!empty($amount))
                @money($value)
            @else
                {{ $value }}                
            @endif
        </a>
    </div>
</div>
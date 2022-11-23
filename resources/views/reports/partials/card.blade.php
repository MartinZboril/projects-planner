<div class="card">
    <div class="card-body">
        <b>{{ $title }}</b> <a class="float-right {{ $colour }}">
            @if (!empty($amount))
                @include('site.partials.amount', ['value' => $value])
            @else
                {{ $value }}                
            @endif
        </a>
    </div>
</div>
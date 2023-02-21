<div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
        <span class="info-box-icon bg-{{ $colour }}">
            <a href="{{ $link }}"><i class="{{ $icon }}"></i></a>
        </span>
        <div class="info-box-content">
            <span class="info-box-text">
                <a href="{{ $link }}">{{ $text }}</a>
            </span>
            <span class="info-box-number">
                @if (!empty($amount))
                    @money($value)
                @else
                    {{ $value }}                
                @endif
            </span>
        </div>
    </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-header">{{ $headline }}</div>
    <div class="card-body">
        <div class="text-center">
            <h6>
                @if (!empty($titleAmount))
                    @money($title)
                @else
                    {{ $title }}                
                @endif
            </h6>
            <span class="text-muted">
                @if (!empty($subtitleAmount))
                    @money($subtitle)
                @else
                    {{ $subtitle }}                
                @endif
            </span>
        </div>
        <div id="{{ $chartId }}" class="mt-2"></div>
    </div>
</div>
<!-- Progress bar values -->
<input type="hidden" class="progress-bar-identifier" value="{{ $chartId }}" />
<input type="hidden" class="progress-bar-colour" value="{{ $colour }}" />
<input type="hidden" class="progress-bar-text" value="{{ $title }}" />
<input type="hidden" class="progress-bar-value" value="{{ $value }}" />
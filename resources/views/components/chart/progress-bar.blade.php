<div class="card card-primary card-outline">
    <div class="card-header">Budget</div>
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

@push('scripts')
    <script>
        var budgetProgressBar = new ProgressBar.Circle('#{{ $chartId }}', {
            strokeWidth: 15,
            color: '{{ $colour }}',
            trailColor: '#eee',
            trailWidth: 15,
            text: {
                value: '{{ $text }}',
                style: {
                    color: '{{ $colour }}',
                    position: 'absolute',
                    left: '50%',
                    top: '50%',
                    padding: 0,
                    margin: 0,
                    fontSize: '1.5rem',
                    fontWeight: 'bold',
                    transform: {
                        prefix: true,
                        value: 'translate(-50%, -50%)',
                    },
                },
            }
        });

        budgetProgressBar.animate({{ $value }});            
    </script>
@endpush
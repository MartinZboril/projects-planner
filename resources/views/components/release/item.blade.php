<div class="time-label ml-2">
    <span class="bg-{{ $release['background'] ? $release['background'] : 'info' }}">{{ $key }}</span>
</div>
<div>
    <div class="timeline-item">
        <span class="time"><i class="fas fa-clock"></i> {{ ($release['realese_date'])->format('d.m.Y') }}</span>
        <h3 class="timeline-header">{{ $release['subject'] }}</h3>
        @if ($release['description'])
            <div class="timeline-body">
                {{ $release['description'] }}
            </div>                                            
        @endif
    </div>
</div>
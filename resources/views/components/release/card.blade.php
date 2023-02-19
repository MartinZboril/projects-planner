<div class="card card-primary card-outline">
    <div class="card-header">Releases</div>
    <div class="card-body">
        @if ($releases->get('releases')->count() > 0)
            <div class="timeline">
                @foreach ($releases->get('releases')->sortByDesc('realese_date') as $key => $release)
                    <x-release.item :$release :$key />
                @endforeach
            </div>                            
        @else
            No releases were found!
        @endif
    </div>
</div>            
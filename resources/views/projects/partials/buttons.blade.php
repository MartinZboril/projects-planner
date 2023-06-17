<a href="#" style="{{ $project->status === App\Enums\ProjectStatusEnum::finish || $project->status === App\Enums\ProjectStatusEnum::archive ? '' : 'display: none;' }}" class="btn btn-{{ $buttonSize }} btn-info" id="project-{{ $project->id }}-active-status" onclick="changeProjectStatus('{{ route('projects.change_status', $project) }}', {{ App\Enums\ProjectStatusEnum::active }}, '{{ $type }}', '{{ __('pages.content.projects.statuses.active') }}', 'info', '{{ $tableIdentifier }}')">
    <x-site.ui.icon icon="fas fa-cogs" :text="$hideButtonText ?? 'Active'" />
</a>
<a href="#" style="{{ $project->status === App\Enums\ProjectStatusEnum::active ? '' : 'display: none;' }}" class="btn btn-{{ $buttonSize }} btn-success" id="project-{{ $project->id }}-finish-status" onclick="changeProjectStatus('{{ route('projects.change_status', $project) }}', {{ App\Enums\ProjectStatusEnum::finish }}, '{{ $type }}', '{{ __('pages.content.projects.statuses.finish') }}', 'success', '{{ $tableIdentifier }}')">
    <x-site.ui.icon icon="fas fa-check" :text="$hideButtonText ?? 'Finish'" />
</a>
<a href="#" style="{{ $project->status != App\Enums\ProjectStatusEnum::finish && $project->status != App\Enums\ProjectStatusEnum::archive ? '' : 'display: none;' }}" class="btn btn-{{ $buttonSize }} btn-primary" id="project-{{ $project->id }}-archive-status" onclick="changeProjectStatus('{{ route('projects.change_status', $project) }}', {{ App\Enums\ProjectStatusEnum::archive }}, '{{ $type }}', '{{ __('pages.content.projects.statuses.archive') }}', 'primary', '{{ $tableIdentifier }}')">
    <i class="fas fa-archive"></i>
</a>
<a href="#" style="{{ Auth::User()->activeTimers->contains('project_id', $project->id) ? '' : 'display: none;' }}" id="project-{{ $project->id }}-stop-work" class="btn btn-{{ $buttonSize }} btn-danger" onclick="stopWorkTimer('{{ Auth::User()->activeTimers->firstWhere('project_id', $project->id)->stop_route ?? '' }}', 'project', '')">
    <x-site.ui.icon icon="fas fa-stop" :text="$hideButtonText ?? 'Stop'" />
</a>
@if (Auth::User()->rates()->count() > 0)
    <div style="{{ !Auth::User()->activeTimers->contains('project_id', $project->id) ? '' : 'display: none;' }}" id="project-{{ $project->id }}-start-work-div" class="btn-group">
        <button type="button" class="btn btn-{{ $buttonSize }} btn-success dropdown-toggle" data-toggle="dropdown">
            Start
        </button>
        <div class="dropdown-menu">
            @foreach (Auth::User()->rates as $rate)
                <a class="dropdown-item" href="#" onclick="startWorkTimer('{{ route('projects.timers.start', $project) }}', {{ $project->id }}, {{ $rate->id }}, '{{ $type }}')">
                    {{ $rate->name }} ({{ $rate->value }})
                </a>
            @endforeach
        </div>
    </div>
@endif
<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="markProject('{{ route('projects.mark', $project) }}', '{{ $type }}', '{{ $tableIdentifier }}')">
    <i class="{{ ($project->is_marked ? 'fas' : 'far') }} fa-bookmark" id="project-{{ $project->id }}-marked"></i>
</a>
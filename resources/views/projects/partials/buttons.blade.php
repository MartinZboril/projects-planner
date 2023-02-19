@if ($project->status === App\Enums\ProjectStatusEnum::active)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-success" onclick="event.preventDefault(); document.getElementById('finish-project-{{ $project->id }}-form').submit();"><x-site.ui.icon icon="fas fa-check" :text="$hideButtonText ?? 'Finish'" /></a>
@elseif ($project->status === App\Enums\ProjectStatusEnum::finish || $project->status === App\Enums\ProjectStatusEnum::archive)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-info" onclick="event.preventDefault(); document.getElementById('active-project-{{ $project->id }}-form').submit();"><x-site.ui.icon icon="fas fa-cogs" :text="$hideButtonText ?? 'Active'" /></a>
@endif
@if ($project->status != App\Enums\ProjectStatusEnum::finish && $project->status != App\Enums\ProjectStatusEnum::archive)
    <a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="event.preventDefault(); document.getElementById('archive-project-{{ $project->id }}-form').submit();"><i class="fas fa-archive"></i></a>
@endif
@if ($project->status === App\Enums\ProjectStatusEnum::active)
    @if(Auth::User()->activeTimers->contains('project_id', $project->id))
        <a href="#" class="btn btn-{{ $buttonSize }} btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-project-{{ $project->id }}').submit();"><x-site.ui.icon icon="fas fa-stop" :text="$hideButtonText ?? 'Stop'" /></a>
    @else
        <div class="btn-group">
            <button type="button" class="btn btn-{{ $buttonSize }} btn-success dropdown-toggle" data-toggle="dropdown">
                Start
            </button>
            <div class="dropdown-menu">
                @foreach (Auth::User()->rates as $rate)
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('start-working-on-project-{{ $project->id }}-with-rate-{{ $rate->id }}').submit();">{{ $rate->name }} ({{ $rate->value }})</a>
                @endforeach
            </div>
        </div>
    @endif
@endif
<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="event.preventDefault(); document.getElementById('{{ ($project->is_marked ? 'unmark' : 'mark') . '-project-' . $project->id . '-form'}}').submit();">
    <i class="{{ ($project->is_marked ? 'fas' : 'far') }} fa-bookmark"></i>
</a>
<!-- Projects forms -->
@include('projects.partials.forms', ['project' => $project])
@include('projects.forms.mark', ['id' => ($project->is_marked ? 'unmark' : 'mark') . '-project-' . $project->id . '-form', 'project' => $project])
@if ($project->status == App\Enums\ProjectStatusEnum::active)
    <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('finish-project-{{ $project->id }}-form').submit();"><i class="fas fa-check mr-1"></i>Finish</a>
@elseif ($project->status == App\Enums\ProjectStatusEnum::finish || $project->status == App\Enums\ProjectStatusEnum::archive)
    <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('active-project-{{ $project->id }}-form').submit();"><i class="fas fa-cogs mr-1"></i>Active</a>
@endif
@if ($project->status != App\Enums\ProjectStatusEnum::finish && $project->status != App\Enums\ProjectStatusEnum::archive)
    <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('archive-project-{{ $project->id }}-form').submit();"><i class="fas fa-archive"></i></a>
@endif
@if(Auth::User()->activeTimers->contains('project_id', $project->id))
    <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-project-{{ $project->id }}').submit();"><i class="fas fa-stop mr-1"></i>Stop</a>
@else
    <div class="btn-group">
        <button type="button" class="btn btn-sm btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
            Start
        </button>
        <div class="dropdown-menu">
            @foreach (Auth::User()->rates as $rate)
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('start-working-on-project-{{ $project->id }}-with-rate-{{ $rate->id }}').submit();">{{ $rate->name }} ({{ $rate->value }})</a>
            @endforeach
        </div>
    </div>
@endif
<!-- Projects forms -->
@include('projects.partials.forms', ['project' => $project])
<a href="{{ route('projects.index') }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-caret-left mr-1"></i>Back</a>
<a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-pencil-alt mr-1"></i>Edit</a>
@if ($project->status == 1)
    <a href="#" class="btn btn-sm btn-success" onclick="event.preventDefault(); document.getElementById('finish-project-form').submit();"><i class="fas fa-check mr-1"></i>Finish</a>
@elseif ($project->status == 2 || $project->status == 3)
    <a href="#" class="btn btn-sm btn-info" onclick="event.preventDefault(); document.getElementById('active-project-form').submit();"><i class="fas fa-cogs mr-1"></i>Active</a>
@endif
@if ($project->status != 2 && $project->status != 3)
    <a href="#" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('archive-project-form').submit();"><i class="fas fa-archive"></i></a>
@endif
@if(Auth::User()->activeTimers->contains('project_id', $project->id))
    <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('stop-working-on-project').submit();"><i class="fas fa-stop mr-1"></i>Stop</a>
@else
    <div class="btn-group">
        <button type="button" class="btn btn-sm btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
            Start
        </button>
        <div class="dropdown-menu">
            @foreach (Auth::User()->rates as $rate)
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('start-working-on-project-with-rate-{{ $rate->id }}').submit();">{{ $rate->name }} ({{ $rate->value }})</a>
            @endforeach
        </div>
    </div>
@endif
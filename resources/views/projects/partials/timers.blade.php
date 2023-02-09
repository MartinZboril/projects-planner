@if ($project->status == App\Enums\ProjectStatusEnum::active)
    @foreach (Auth::User()->rates as $rate)
        @include('projects.timers.forms.start', ['id' => 'start-working-on-project-' . $project->id . '-with-rate-' . $rate->id, 'project' => $project, 'rateId' => $rate->id])            
    @endforeach

    @if(Auth::User()->activeTimers->contains('project_id', $project->id))
        @include('projects.timers.forms.stop', ['id' => 'stop-working-on-project-' . $project->id, 'project' => $project, 'timer' => Auth::User()->activeTimers->firstWhere('project_id', $project->id)])            
    @endif
@endif
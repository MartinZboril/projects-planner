@foreach (Auth::User()->rates as $rate)
    @include('timers.forms.start', ['id' => 'start-working-on-project-' . $project->id . '-with-rate-' . $rate->id, 'projectId' => $project->id, 'rateId' => $rate->id])            
@endforeach

@if(Auth::User()->activeTimers->contains('project_id', $project->id))
    @include('timers.forms.stop', ['id' => 'stop-working-on-project-' . $project->id, 'timerId' => Auth::User()->activeTimers->firstWhere('project_id', $project->id)->id])            
@endif
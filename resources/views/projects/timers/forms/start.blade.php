<form id="{{ $id }}" action="{{ route('projects.timers.start', $project) }}" method="POST" class="hidden">
    @csrf
    @method('POST')
    <input type="hidden" name="project_id" value="{{ $project->id }}">
    <input type="hidden" name="rate_id" value="{{ $rateId }}">
</form>
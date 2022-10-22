<form id="{{ $id }}" action="{{ route('timers.start') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="project_id" value="{{ $projectId }}">
    <input type="hidden" name="rate_id" value="{{ $rateId }}">
</form>
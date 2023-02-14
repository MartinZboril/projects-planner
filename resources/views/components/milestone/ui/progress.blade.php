<div class="progress progress-sm">
    <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="{{ $milestone->progress * 100 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $milestone->progress * 100 }}%"></div>
</div>
<small>{{ $milestone->progress * 100 }} % Complete ({{ $milestone->tasksCompleted->count() }}/{{ $milestone->tasks->count() }})</small>
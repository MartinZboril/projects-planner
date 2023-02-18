<form id="{{ $id }}" action="{{ route('projects.milestones.mark', ['project' => $milestone->project, 'milestone' => $milestone]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>
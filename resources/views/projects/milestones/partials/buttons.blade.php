<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="markMilestone('{{ route('projects.milestones.mark', ['project' => $milestone->project, 'milestone' => $milestone]) }}', '{{  $type }}', '{{ $tableIdentifier }}')">
    <i class="{{ ($milestone->is_marked ? 'fas' : 'far') }} fa-bookmark pt-1 pb-1" id="milestone-{{ $milestone->id }}-marked"></i>
</a>
<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="markMilestone('{{ route('projects.milestones.mark', ['project' => $milestone->project, 'milestone' => $milestone]) }}', '{{  $type }}', '{{ $tableIdentifier }}')">
    <i class="{{ ($milestone->is_marked ? 'fas' : 'far') }} fa-bookmark pt-1 pb-1" id="milestone-{{ $milestone->id }}-marked"></i>
</a>
<a href="#" class="btn btn-{{ $buttonSize }} btn-danger" onclick="deleteMilestone('{{ route('projects.milestones.destroy', ['project' => $milestone->project, 'milestone' => $milestone]) }}', '{{ $type }}', '{{ $tableIdentifier }}', '{{ $redirect }}')">
    <x-site.ui.icon icon="fas fa-trash" :text="$hideButtonText ?? 'Delete'" />
</a>
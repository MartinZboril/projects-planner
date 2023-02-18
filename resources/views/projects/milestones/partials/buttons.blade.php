<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="event.preventDefault(); document.getElementById('{{ ($milestone->is_marked ? 'unmark' : 'mark') . '-milestone-' . $milestone->id . '-form'}}').submit();">
    <i class="{{ ($milestone->is_marked ? 'fas' : 'far') }} fa-bookmark"></i>
</a>
@include('projects.milestones.forms.mark', ['id' => ($milestone->is_marked ? 'unmark' : 'mark') . '-milestone-' . $milestone->id . '-form', 'milestone' => $milestone])

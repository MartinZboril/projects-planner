<a href="#" class="btn btn-{{ $buttonSize }} btn-primary" onclick="event.preventDefault(); document.getElementById('{{ ($milestone->is_marked ? 'unmark' : 'mark') . '-milestone-' . $milestone->id . '-form'}}').submit();">
    <i class="{{ ($milestone->is_marked ? 'fas' : 'far') }} fa-bookmark pt-1 pb-1"></i>
</a>
@include('projects.milestones.forms.mark', ['id' => ($milestone->is_marked ? 'unmark' : 'mark') . '-milestone-' . $milestone->id . '-form'])

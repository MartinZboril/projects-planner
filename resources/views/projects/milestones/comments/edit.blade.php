<form action="{{ route('projects.milestones.comments.update', ['project' => $project, 'milestone' => $milestone, 'comment' => $comment]) }}" method="post" id="edit-form-comment-{{ $comment->id }}" class="d-none" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('projects.milestones.comments.forms.fields', ['comment' => $comment, 'type' => 'edit'])
</form>
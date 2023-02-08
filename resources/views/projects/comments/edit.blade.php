<form action="{{ route('projects.comments.update', ['project' => $project, 'comment' => $comment]) }}" method="post" id="edit-form-comment-{{ $comment->id }}" class="d-none" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('projects.comments.forms.fields', ['comment' => $comment, 'type' => 'edit'])
</form>
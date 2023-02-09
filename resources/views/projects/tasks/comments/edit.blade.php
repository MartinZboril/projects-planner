<form action="{{ route('projects.tasks.comments.update', ['project' => $project, 'task' => $task, 'comment' => $comment]) }}" method="post" id="edit-form-comment-{{ $comment->id }}" class="d-none" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('projects.tasks.comments.forms.fields', ['comment' => $comment, 'type' => 'edit'])
</form>
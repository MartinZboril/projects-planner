<form action="{{ route('tasks.comments.update', ['task' => $task, 'comment' => $comment]) }}" method="post" id="edit-form-comment-{{ $comment->id }}" class="d-none" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('tasks.comments.forms.fields', ['comment' => $comment, 'type' => 'edit'])
</form>
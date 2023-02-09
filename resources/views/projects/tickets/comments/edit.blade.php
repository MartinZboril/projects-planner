<form action="{{ route('projects.tickets.comments.update', ['project' => $project, 'ticket' => $ticket, 'comment' => $comment]) }}" method="post" id="edit-form-comment-{{ $comment->id }}" class="d-none" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('projects.tickets.comments.forms.fields', ['comment' => $comment, 'type' => 'edit'])
</form>
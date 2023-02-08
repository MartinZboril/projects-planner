<form action="{{ route('tickets.comments.update', ['ticket' => $ticket, 'comment' => $comment]) }}" method="post" id="edit-form-comment-{{ $comment->id }}" class="d-none" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('tickets.comments.forms.fields', ['comment' => $comment, 'type' => 'edit'])
</form>
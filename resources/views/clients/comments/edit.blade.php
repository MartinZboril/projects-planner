<form action="{{ route('clients.comments.update', ['client' => $client, 'comment' => $comment]) }}" method="post" id="edit-form-comment-{{ $comment->id }}" class="d-none" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('clients.comments.forms.fields', ['comment' => $comment, 'type' => 'edit'])
</form>
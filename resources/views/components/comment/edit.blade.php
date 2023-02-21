<form action="{{ $comment->update_route }}" method="post" id="edit-form-comment-{{ $comment->id }}" class="d-none" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <x-comment.fields type="edit" :$comment />
</form>
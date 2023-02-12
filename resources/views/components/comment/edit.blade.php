<form action="{{ route($updateFormRouteName, [$parent, 'comment' => $comment]) }}" method="post" id="edit-form-comment-{{ $comment->id }}" class="d-none" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <x-comment.fields type="edit" :$comment />
</form>
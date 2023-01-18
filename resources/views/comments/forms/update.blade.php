<form action="{{ route('comments.update', $comment->id) }}" method="post" id="edit-form-comment-{{ $comment->id }}" class="d-none">
    @csrf
    @method('PATCH')
    @include('comments.forms.fields', ['comment' => $comment, $parentId => null, 'parentType' => null, 'type' => 'edit'])
</form>
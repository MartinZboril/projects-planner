<div class="timeline">
    @include('comments.forms.store', ['comment' => $comment, 'parentId' => $parentId, 'parentType' => $parentType])
    @foreach ($comments as $comment)
        @include('comments.partials.comment', ['comment' => $comment])
    @endforeach
</div>                                                    

@push('scripts')
    <script>
        function updateContentView(mode, id) {
            const commentContent = document.querySelector('#content-comment-' + id);
            const commentEdit = document.querySelector('#edit-form-comment-' + id);
            const commentContentEditor = document.querySelector('#content-editor-comment-' + id);
            const commentFooter = document.querySelector('#footer-comment-' + id);   

            if (mode === 'edit') {   
                // Summernote editor          
                $(commentContentEditor).summernote({height: 200});
                commentContent.classList.add('d-none');
                commentEdit.classList.remove('d-none');
                commentFooter.classList.add('d-none');
            } else if (mode === 'display') {
                commentContent.classList.remove('d-none');
                commentEdit.classList.add('d-none');
                commentFooter.classList.remove('d-none');
                // Summernote editor
                $(commentContentEditor).summernote('destroy');
            }
        }
    </script>
@endpush
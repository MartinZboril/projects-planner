<div class="card card-primary card-outline">
    @if($displayHeader)
        <div class="card-header">Comments</div>
    @endif
    <div class="card-body">
        <div class="timeline">
            <x-comment.create :$storeFormRoute />
            @foreach ($comments as $comment)
                <x-comment.item :$comment />
            @endforeach
        </div>                                                        
    </div>
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
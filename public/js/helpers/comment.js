function updateContentView(mode, id) {
    const commentContent = $('#content-comment-' + id);
    const commentEdit = $('#edit-form-comment-' + id);
    const commentContentEditor = $('#content-editor-comment-' + id);
    const commentFooter = $('#footer-comment-' + id);   
    // change comment view
    if (mode === 'edit') {   
        // Summernote editor          
        $(commentContentEditor).summernote({height: 200});
        commentContent.addClass('d-none');
        commentEdit.removeClass('d-none');
        commentFooter.addClass('d-none');
    } else if (mode === 'display') {
        commentContent.removeClass('d-none');
        commentEdit.addClass('d-none');
        commentFooter.removeClass('d-none');
        // Summernote editor
        $(commentContentEditor).summernote('destroy');
    }
}
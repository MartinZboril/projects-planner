// Delete comment
function deleteComment(url) {
    if (!confirm('Do you really want to remove comment?')) return false;

    const token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: 'DELETE',
        data: {
            "_token": token,
        },
        error: function() {
            toastr.error('An error has occurred!');
        },
        success: function (data) {
            sessionStorage.setItem('message', data.message)
            sessionStorage.setItem('type', 'info')
            window.location.reload(); 
        }
    });
}
// Update content view to show edit form
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
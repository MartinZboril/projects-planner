// Delete file
function deleteFile(url) {
    if (!confirm('Do you really want to remove file?')) return false;

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
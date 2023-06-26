
// Mark note
function markNote(url, redirect) {
    const token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: 'PATCH',
        data: {
            "_token": token,
            "redirect": redirect,
        },
        error: function() {
            toastr.error('An error has occurred!');
        },
        success: function (data) {
            window.location.href = data.redirect;
        }
    });
}
// Delete note
function deleteNote(url) {
    if (!confirm('Do you really want to remove note?')) return false;

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
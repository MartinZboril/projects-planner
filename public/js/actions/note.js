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
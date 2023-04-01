// Pause task
function pauseTask(url) {
    const token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: 'PATCH',
        data: {
            "_token": token,
        },
        error: function() {
            toastr.error('An error has occurred!');
        },
        success: function (data) {
            toastr.info('', data.message, {
                timeOut: 500,
                preventDuplicates: true,
                // Redirect 
                onHidden: function() {
                    window.location.reload();
                }
            });
        }
    });
}
// Change task status
function changeTaskStatus(url, status) {
    const token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: 'PATCH',
        data: {
            "status": status,
            "_token": token,
        },
        error: function() {
            toastr.error('An error has occurred!');
        },
        success: function(data) {
            toastr.info('', data.message, {
                timeOut: 500,
                preventDuplicates: true,
                // Redirect 
                onHidden: function() {
                    window.location.reload();
                }
            });
        },
    });
}
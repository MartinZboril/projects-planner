// Delete role
function deleteRole(url, type, tableIdentifier = '#roles-table', redirect) {
    if (!confirm('Do you really want to remove role?')) return false;

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
            console.log(redirect);
            if (type === 'table') {
                $(tableIdentifier).DataTable().ajax.reload(); 
            } else {
                window.location.href = redirect ? redirect : window.location.href;
            }
            toastr.info(data.message);
        }
    });
}
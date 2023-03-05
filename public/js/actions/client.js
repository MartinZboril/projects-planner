function markClient(url, type) {
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
            if (type === 'table') {
                $('#clients-table').DataTable().ajax.reload(); 
            } else {
                const id = data.client.id;
                const marked = data.client.is_marked;   
                const newFill = marked ? 'fas' : 'far';
                const oldFill = marked ? 'far' : 'fas';
                // Marked icon
                const markedIcon = $('#client-' + id + '-marked');                               
                // Update tickets view
                updateCssClass(markedIcon, newFill, oldFill);
            }
            toastr.info(data.message);
        }
    });
}
// Update element
function updateCssClass(element, addClass, removeClass) {
    // Add element class if it doesn't exist
    if (!element.hasClass(addClass)) {
        element.addClass(addClass);
    }
    // Remove element class if it exists                        
    if (element.hasClass(removeClass)) {
        element.removeClass(removeClass);
    }
}
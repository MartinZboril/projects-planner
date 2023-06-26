// Mark milestone
function markMilestone(url, type, tableIdentifier = '#milestones-table') {
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
                $(tableIdentifier).DataTable().ajax.reload(); 
            } else {
                const id = data.milestone.id;
                const marked = data.milestone.is_marked;   
                const newFill = marked ? 'fas' : 'far';
                const oldFill = marked ? 'far' : 'fas';
                // Marked icon
                const markedIcon = $('#milestone-' + id + '-marked');                               
                // Update tickets view
                updateCssClass(markedIcon, newFill, oldFill);
            }
            toastr.info(data.message);
        }
    });
}
// Delete milestone
function deleteMilestone(url, type, tableIdentifier = '#milestones-table', redirect) {
    if (!confirm('Do you really want to remove milestone?')) return false;

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
            if (type === 'table') {
                $(tableIdentifier).DataTable().ajax.reload(); 
            } else {
                window.location.href = redirect ? redirect : window.location.href;
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
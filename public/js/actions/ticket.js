// Change ticket status
function changeTicketStatus(url, status, type, featureText, featureBadge) {
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
        success: function (data) {
            if (type === 'table') {
                $('#tickets-table').DataTable().ajax.reload(); 
            } else {
                const id = data.ticket.id;
                const status = data.ticket.status;
                const overdue = data.ticket.overdue; 
                const converted = data.ticket.is_convert;
                const assignee = data.ticket.assignee_id;
                // Buttons Ids
                const openButton = $('#ticket-' + id + '-open-status');
                const closeButton = $('#ticket-' + id + '-close-status');
                const archiveButton = $('#ticket-' + id + '-archive-status');
                const convertToTaskButton = $('#ticket-' + id + '-convert-to-task');
                // Modify user view
                switch (status) {
                    case 1:
                        openButton.hide();
                        closeButton.show();
                        archiveButton.show();                
                        break;
                    case 2:
                        openButton.show();
                        closeButton.hide();
                        archiveButton.hide();
                        break;
                    case 3:
                        openButton.show();
                        closeButton.hide();
                        archiveButton.hide();
                        break;
                    default:
                        openButton.hide();
                        closeButton.hide();
                        archiveButton.hide();
                }
                // Display convert to task
                if (!converted && assignee && status != 2 && status != 3) {
                    convertToTaskButton.show();
                } else {
                    convertToTaskButton.hide();
                }

                // Change status text
                $('#ticket-status-badge').html('<span class="badge badge-' + featureBadge + ' ml-2" style="font-size:14px;">' + featureText + '</span>');
                $('#ticket-status-text').html(featureText);
                // Change overdue informations
                const dueDateBadge = $('#ticket-' + id + '-due-date');    
                const overdueBadge = $('#ticket-' + id + '-overdue-badge');                
                if (overdue) {
                    overdueBadge.show();
                    updateCssClass(dueDateBadge, 'badge-danger', 'badge-secondary')
                } else {
                    overdueBadge.hide();    
                    updateCssClass(dueDateBadge, 'badge-secondary', 'badge-danger')
                }
            }
            toastr.info(data.message);
        }
    });
}
// Convert Ticket
function convertTicket(url) {
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
        success: function (data){
            window.location.href = data.redirect;
        }
    });
}
// Mark Ticket
function markTicket(url, type) {
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
        success: function (data){
            if (type === 'table') {
                $('#tickets-table').DataTable().ajax.reload(); 
            } else {
                const id = data.ticket.id;
                const marked = data.ticket.is_marked;   
                const newFill = marked ? 'fas' : 'far';
                const oldFill = marked ? 'far' : 'fas';     
                // Marked icon
                const markedIcon = $('#ticket-' + id + '-marked');              
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
// Change project status
function changeProjectStatus(url, status, type, featureText, featureBadge) {
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
            const id = data.project.id;
            const status = data.project.status;
            const overdue = data.project.overdue; 
            const deadline = data.project.deadline + ' day(s)'; 
            // Buttons Ids
            const activeButton = $('#project-' + id + '-active-status');
            const finishButton = $('#project-' + id + '-finish-status');
            const archiveButton = $('#project-' + id + '-archive-status');
            // Modify user view
            switch (status) {
                case 1:
                    activeButton.hide();
                    finishButton.show();
                    archiveButton.show();              
                    break;
                case 2:
                    activeButton.show();
                    finishButton.hide();
                    archiveButton.hide();
                    break;
                case 3:
                    activeButton.show();
                    finishButton.hide();
                    archiveButton.hide();
                    break;
                default:
                    activeButton.hide();
                    finishButton.hide();
                    archiveButton.hide();
            }
            // Change status text
            if (type === 'detail') {
                // Change status badge
                $('#project-status-badge').html('<span class="badge badge-' + featureBadge + ' ml-2" style="font-size:14px;">' + featureText + '</span>');
                $('#project-status-text').html(featureText);
                // Change overdue informations
                const deadlineDiv = $('#project-' + id + '-deadline');
                const deadlineBadge = $('#project-' + id + '-deadline-badge');    
                const overdueBadge = $('#project-' + id + '-overdue-badge');
                deadlineBadge.html(deadline);
                if (status === 1) {
                    if (overdue) {
                        overdueBadge.show();
                        updateCssClass(deadlineBadge, 'badge-danger', 'badge-success')
                    } else {
                        overdueBadge.hide();    
                        updateCssClass(deadlineBadge, 'badge-success', 'badge-danger')
                    }
                    deadlineDiv.show();                    
                } else {
                    deadlineDiv.hide();
                    overdueBadge.hide();    
                }               
            }
            toastr.info(data.message);
        }
    });
}
// Mark project
function markProject(url) {
    var token = $('meta[name="csrf-token"]').attr('content');
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
            const id = data.project.id;
            const marked = data.project.is_marked;   
            const newFill = marked ? 'fas' : 'far';
            const oldFill = marked ? 'far' : 'fas';     
            // Marked icon
            const markedIcon = $('#project-' + id + '-marked');              
            // Update projects view
            updateCssClass(markedIcon, newFill, oldFill);
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
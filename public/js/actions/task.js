// Pause task
function pauseTask(url, type, featureText, featureBadge) {
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
            const id = data.task.id;
            const paused = data.task.paused;
            const overdue = data.task.overdue; 
            // Button Ids
            const resumeButton = $('#task-' + id + '-resume');
            const stopButton = $('#task-' + id + '-stop');
            const completeButton = $('#task-' + id + '-complete-status');
            // Modify user view
            if (paused) {
                // Add disabled class from task complete button
                if (!completeButton.hasClass('disabled')) {
                    completeButton.addClass('disabled');
                }
                // Update pause buttons visibility
                resumeButton.show();
                stopButton.hide();
            } else {
                // Remove disabled class from task complete button
                if (completeButton.hasClass('disabled')) {
                    completeButton.removeClass('disabled');
                }
                // Update pause buttons visibility
                resumeButton.hide();
                stopButton.show();
            }
            // Change status text
            if (type === 'detail') {
                modifyStatusText(featureText, featureBadge);
                modifyOverdue(id, overdue);
            }
            toastr.info(data.message);
        }
    });
}
// Change task status
function changeTaskStatus(url, status, type, featureText, featureBadge) {
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
            const id = data.task.id;
            const status = data.task.status;
            const overdue = data.task.overdue; 
            // Buttons Ids
            const newButton = $('#task-' + id + '-new-status');
            const completeButton = $('#task-' + id + '-complete-status');
            const returnButton = $('#task-' + id + '-return-status');
            // Pause div Id
            const pauseDiv = $('#task-' + id + '-pause-div');
            // Modify user view
            switch (status) {
                case 1:
                    newButton.show();
                    completeButton.hide();
                    returnButton.hide();
                    pauseDiv.hide();
                    break;
                case 2:
                    newButton.hide();
                    completeButton.show();
                    returnButton.hide();
                    pauseDiv.show();
                    break;
                case 3:
                    newButton.hide();
                    completeButton.hide();
                    returnButton.show();
                    pauseDiv.hide();
                    break;
                default:
                    newButton.hide();
                    completeButton.hide();
                    returnButton.hide();
                    pauseDiv.hide();                            
            }
            // Change status text
            if (type === 'detail') {
                modifyStatusText(featureText, featureBadge);
                modifyOverdue(id, overdue);
            }
            toastr.info(data.message);
        }
    });
}
// Modify status text
function modifyStatusText(featureText, featureBadge) {
    $('#task-status-badge').html('<span class="badge badge-' + featureBadge + ' ml-2" style="font-size:14px;">' + featureText + '</span>');
    $('#task-status-text').html(featureText);
}
// Modify ovedue text
function modifyOverdue(id, overdue) {
    // Change overdue informations
    const dueDateBadge = $('#task-' + id + '-due-date');    
    const overdueBadge = $('#task-' + id + '-overdue-badge');                
    if (overdue) {
        overdueBadge.show();
        updateCssClass(dueDateBadge, 'badge-danger', 'badge-secondary')
    } else {
        overdueBadge.hide();    
        updateCssClass(dueDateBadge, 'badge-secondary', 'badge-danger')
    }
}
// Mark task
function markTask(url, type) {
    const token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url,
        type: 'post',
        data: {
            "_method": "PATCH",
            "_token": token,
        },
        error: function() {
            toastr.error('An error has occurred!');
        },
        success: function (data) {
            const id = data.task.id;
            const marked = data.task.is_marked;   
            const newFill = marked ? 'fas' : 'far';
            const oldFill = marked ? 'far' : 'fas';  
            // Marked icon
            const markedIcon = $('#task-' + id + '-marked');
            // Update tasks view
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

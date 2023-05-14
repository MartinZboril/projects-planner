// Check ToDo
function checkTodo(url, type = '') {
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
            if (type === 'list') {
                $('#todo-item-' + data.todo.id).remove();
                const remainingToDosCount = $('#todo-items-list').children().length;
                $('#todo-items-count-list').html(remainingToDosCount);
                if (remainingToDosCount === 0) {
                    $('#todo-list').hide();
                }
            } else if (type === 'summary') {
                $('#summary-item-todo-' + data.todo.id).remove();
                const remainingSummaryCount = $('#summary-items-list').children().length;
                $('#summary-items-count-list').html(remainingSummaryCount);
                if (remainingSummaryCount === 0) {
                    $('#summary-list').hide();
                }
            } else {
                const id = data.todo.id;
                const overdue = data.todo.overdue;   
                // Due date badge
                const dueDateBadge = $('#todo-' + id + '-due-date');                 
                if (overdue) {
                    updateCssClass(dueDateBadge, 'badge-danger', 'badge-secondary');
                }
            }
            toastr.info(data.message);
        }
    });
}
// Destroy ToDo
function destroyTodo(url, type = '') {
    if (!confirm('Do you really want to remove todo?')) return false;

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
        success: function (data){
            if (type === 'list') {
                $('#todo-item-' + data.todo.id).remove();
                const remainingToDosCount = $('#todo-items-list').children().length;
                $('#todo-items-count-list').html(remainingToDosCount);
                if (remainingToDosCount === 0) {
                    $('#todo-list').hide();
                }
            } else if (type === 'summary') {
                $('#summary-item-todo-' + data.todo.id).remove();
                const remainingSummaryCount = $('#summary-items-list').children().length;
                $('#summary-items-count-list').html(remainingSummaryCount);
                if (remainingSummaryCount === 0) {
                    $('#summary-list').hide();
                }
            } else if (type === 'card') {
                $('#todo-item-' + data.todo.id).remove();
            }
            toastr.error(data.message);
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
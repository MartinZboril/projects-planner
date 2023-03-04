function checkTodo(url) {
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
            const id = data.todo.id;
            const overdue = data.todo.overdue;   
            // Due date badge
            const dueDateBadge = $('#todo-' + id + '-due-date');                 
            if (overdue) {
                updateCssClass(dueDateBadge, 'badge-danger', 'badge-secondary');
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
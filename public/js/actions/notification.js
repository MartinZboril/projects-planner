// Seen notification
function seenNotification(url, notification) {
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
            toastr.info(data.message);
            $('.notification-' + notification + '-item').remove();
            // Reduce notifications count
            let unreadNotificationCount = parseInt($('.unread-notifications-count').html());
            $('.unread-notifications-count').html(--unreadNotificationCount);
        }
    });
}
// Seen all notification
function seenAllNotifications(url) {
    if (!confirm('Do you really want to marked as read all notifications?')) return false;

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
            sessionStorage.setItem('message', data.message)
            sessionStorage.setItem('type', 'info')
            window.location.reload();
        }
    });
}
// View notification link
function viewNotificationLink(url, link) {
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
        success: () => window.location.href = link
    });
}

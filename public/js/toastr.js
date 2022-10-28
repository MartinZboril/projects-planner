$(function () {
    var content = $('#message-content').val();
    var type = $('#message-type').val();

    if(content) {
        switch (type) { 
            case 'success': 
                toastr.success(content);
                break;
            case 'info': 
                toastr.info(content);
                break;
            case 'warning': 
                toastr.warning(content);
                break;		
            case 'danger': 
                toastr.error(content);
                break;
            default:
                toastr.info(content);
        }
    }; 
});
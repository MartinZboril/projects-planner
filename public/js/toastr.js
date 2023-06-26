$(function () {
    let content, type;

    if (sessionStorage.getItem('message') && sessionStorage.getItem('type')) {
        content = sessionStorage.getItem('message');
        type = sessionStorage.getItem('type');  

        sessionStorage.removeItem('message');
        sessionStorage.removeItem('type');
    } else {
        content = $('#message-content').val();
        type = $('#message-type').val();    
    }

    if(content && type) {
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
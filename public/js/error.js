$(function () {
    var error = $('#error-content').val();

    if(error) {
        toastr.error(error);
    }; 
});
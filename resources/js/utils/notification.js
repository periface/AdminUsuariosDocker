
const showNotification = (title, message, type = 'success') => {

    toastr.options = {
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    switch(type) {
        case 'error':
            toastr.error(message, title);
            break;

        case 'warning':
            toastr.error(message, title);
            break;

        case 'info':
            toastr.error(message, title);
            break;
        default:
            toastr.success(message, title);
            break;
    }

}

export { showNotification };
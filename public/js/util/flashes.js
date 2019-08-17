$(document).ready(function () {
    $('.flashMessages').each(function () {
        var message = $(this).attr('data-success');
        $.notify({
            icon: 'fa fa-paw',
            message: message,
        }, {
                placement: {
                    align: 'center',
                    from: 'top',
                },
                animate: {
                    enter: "animated fadeInDown",
                    exit: "animated fadeOutUp"
                },
            type: 'success'
        });
    });


});
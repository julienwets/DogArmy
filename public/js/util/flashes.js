$(document).ready(function () {
    $('.flashSuccess').each(function () {
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
            type: 'secondary'
        });
    });
    
    $('.flashDanger').each(function () {
        var message = $(this).attr('data-danger');
        $.notify({
            icon: 'fas fa-dog',
            title: 'Erreur !',
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
            type: 'dark'
        });
    });

});
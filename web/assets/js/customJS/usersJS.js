$(document).ready(function () {
    var ias = jQuery.ias({
        container: '.box-users',
        item: '.user-item',
        pagination: '.pagination',
        next: '.pagination .next_link',
        triggerPageThreshold: 5
    });

    ias.extension(new IASTriggerExtension({
        text: 'Ver más',
        offset: 3
    }));

    ias.extension(new IASSpinnerExtension({
        src: loader
    }));

    ias.extension(new IASNoneLeftExtension({
        text: 'No hay más usuarios'
    }));
});
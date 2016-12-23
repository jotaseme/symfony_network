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

    ias.on('ready',function (event) {
        followUser();
    });

    ias.on('rendered',function (event) {
        followUser();
    });


});

function followUser() {
    var path_follow = Routing.generate('follow', true);
    var path_unfollow = Routing.generate('unfollow', true);
    $(".btn-follow").unbind("click").click(function () {
        $(this).addClass("hidden");
        $(this).parent().find(".btn-unfollow").removeClass("hidden");
        $.ajax({
            url: path_follow,
            type: 'POST',
            data: { followed: $(this).attr("data-followed") },
            success: function(response){
                console.log(response);
            }
        });
    });

    $(".btn-unfollow").unbind("click").click(function () {
        $(this).addClass("hidden");
        $(this).parent().find(".btn-follow").removeClass("hidden");
        $.ajax({
            url: path_unfollow,
            type: 'POST',
            data: { followed: $(this).attr("data-followed") },
            success: function(response){
                console.log(response);
            }
        });
    });
}
$(document).ready(function () {
    var ias = jQuery.ias({
        container: '.profile-box #user-publication',
        item: '.publication-item',
        pagination: '.profile-box .pagination',
        next: '.profile-box .pagination .next_link',
        triggerPageThreshold: 5
    });

    ias.extension(new IASTriggerExtension({
        text: 'Ver más publicaciones',
        offset: 3
    }));

    ias.extension(new IASSpinnerExtension({
        src: loader
    }));

    ias.extension(new IASNoneLeftExtension({
        text: 'No hay más publicaciones'
    }));

    ias.on('ready',function (event) {
        buttons();
    });

    ias.on('rendered',function (event) {
        buttons();
    });


});

function buttons() {
    $('[data-toggle="tooltip"]').tooltip();

    $(".btn-img").unbind("click").click(function(){
        $(this).parent().find('.pub-image').fadeToggle();
    });

    $(".btn-delete-pub").unbind("click").click(function(){
        var path_remove = Routing.generate('remove_publication',{ id: $(this).attr("data-id") },true);
        $(this).parent().parent().addClass('hidden');

        $.ajax({
            url: path_remove,
            type:'GET',
            success: function(response){
                $("#delete-pub").empty();
                $("#delete-pub").addClass("alert alert-info").append(response);
            }
        })
    });

    $(".btn-like").unbind("click").click(function(){
        $(this).addClass("hidden");
        $(this).parent().find(".btn-unlike").removeClass("hidden");
        var path_like = Routing.generate('like',{ id: $(this).attr("data-id") },true);
        $.ajax({
            url: path_like,
            type: 'GET',
            success: function(response){
                console.log(response);
            }
        })
    });

    $(".btn-unlike").unbind("click").click(function(){
        $(this).addClass("hidden");
        $(this).parent().find(".btn-like").removeClass("hidden");
        var path_dislike = Routing.generate('dislike',{ id: $(this).attr("data-id") },true);
        $.ajax({
            url: path_dislike,
            type: 'GET',
            success: function(response){
                console.log(response);
            }
        })
    });


}
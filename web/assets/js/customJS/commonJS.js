$(document).ready(function(){

    notifications();
    setInterval(function(){notifications()},60000)

});


function notifications(){
    var path = Routing.generate('notifications_counter', true);
    $.ajax({
        url:path,
        type: 'GET',
        success: function (response) {
            $(".label-notifications").html(response);
            if($(".label-notifications").text()==0){
                $(".label-notifications").addClass("hidden");
            }else{
                $(".label-notifications").removeClass("hidden");
            }
        }
    });
}
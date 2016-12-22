$(document).ready(function () {

    $(".nick-input").blur(function () {
        var path = Routing.generate('nickTest', true)
        var nick = this.value;
        $.ajax({
            url: path,
            data: {nick: nick},
            type: 'POST',
            success: function (response) {
                if(response == "used"){
                    $("#errorNick").removeClass("alert alert-info").empty();
                    $(".nick-input").css("border","1px solid red");
                    $("#backendbundle_user_Registrar").prop('disabled', true);

                    $("#errorNick").addClass("alert alert-info").append("¡El nick que has introducido ya existe!");
                }else{
                    $("#backendbundle_user_Registrar").prop('disabled', false);
                    $(".nick-input").css("border","1px solid green");
                    $("#errorNick").removeClass("alert alert-info").empty();
                }
            }
        });
    });
})
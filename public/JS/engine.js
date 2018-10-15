$(document).ready(function(){
    $('.buyme').on('click', function(){
        var id_good = $(this).attr("id").substr(5);

        $.ajax({
            url: "/basket/add/",
            type: "POST",
            data:{
                id_good: id_good,
                quantity: 1
            },
            error: function() {alert("Что-то пошло не так...");},
            success: function(answer){
                if(answer.result == 1)
                    alert("Товар добавлен в корзину!");
                else
                    alert("Что-то пошло не так...");
            },
            dataType : "json"
        })
    });

    $('.remove').on('click', function(){
        var id_basket = $(this).attr("id").substr(7);

        $.ajax({
            url: "/basket/remove/",
            type: "POST",
            data:{
                id_basket: id_basket,
            },
            error: function() {alert("Что-то пошло не так...");},
            success: function(answer){
                if(answer.result == 1)
                    alert("Товар удалён из корзины!");
                else
                    alert("Что-то пошло не так...");
            },
            dataType : "json"
        })
    });
});


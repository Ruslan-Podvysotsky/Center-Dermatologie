/**
 * Created by User on 14.05.2017.
 */
                // форма
$(document).ready(function() {
    var $overlay = $(".overlay");
    var $closeBtn = $(".formWrapper__closeBtn");

    $("#callback-btn").on("click", function() {
        $overlay.show();
    });

    $closeBtn.on("click", function() {
        $overlay.hide();
    });

                // всплывающие меню
    var options = {
        offset: 150
    };
    var nav = new Headhesive('.nav',options);

                // МАСКА
    $("#phone").mask("+38(099)999-99-99");

                // СЛАЙДЕР
    if(window.matchMedia('(min-width: 984px)').matches){
        $('.bxslider').bxSlider({
            slideWidth: 321,
            minSlides: 1,
            maxSlides: 3,
            muveslides: 3,
            controls: true,
            touchEnabled: true,
            infiniteLoop: true,
            responsive: true
        });
    }
    if(window.matchMedia('(min-width: 768px)').matches){
        $('.bxslider').bxSlider({
            slideWidth: 227,
            minSlides: 2,
            maxSlides: 3,
            muveslides: 3,
            controls: true,
            touchEnabled: true,
            infiniteLoop: true,
            responsive: true
        });
    }
    if(window.matchMedia('(min-width: 320px)').matches){
        $('.bxslider').bxSlider({
            slideWidth: 130,
            minSlides: 2,
            maxSlides: 2,
            muveslides: 2,
            controls: true,
            touchEnabled: true,
            infiniteLoop: true,
            responsive: true
        });
    }


    $('#callbackform').submit(function(event){

        event.preventDefault();

        var fio = $("input#fio").val();
        var email = $("input#email").val();
        var phone = $("input#phone").val();
        var description = $("textarea#description").val();
        
        if (fio.length === 0) {
            $("input#fio").addClass('warning');
            return false;
        }

        
        if (email.length === 0) {
            $("input#email").addClass('warning');
            return false;
        }

        if (phone.length === 0) {
            $("input#phone").addClass('warning');
            return false;
        }

        var payload = {
            "fio": fio,
            "email":email,
            "phone": phone,
            "description": description
        };
        

        $.ajax({
            type: "POST",
            url: "php/main.php",
            data: payload,
            success: function(data) {
                $('#notified').html(data);
            }
        });

        return false;
    });
});
$(function () {
    $('#battle').on('click', function () {
        let url = '/space/result';
        let post = 'ship1_id=' + $('#ship1_id').val();
        let battle_result = $('#battle_result');
        post += '&ship1_quantity=' + $('#ship1_quantity').val();
        post += '&ship2_id=' + $('#ship2_id').val();
        post += '&ship2_quantity=' + $('#ship2_quantity').val();
        post += '&battle_type=' + $('#battle_type').val();
        $.ajax({
            dataType: "text",
            method: "POST",
            data: post,
            url: url,
            success: function (data) {
                battle_result.html(data);
            },
            error: function (jqXHR) {
                battle_result.text(' ' + jqXHR.statusText);
            },
            complete: function () {
                $('html, body').animate({
                    scrollTop: battle_result.offset().top
                }, {
                    duration: 1000,
                    easing: "linear"
                });
            }
        });
    });
})
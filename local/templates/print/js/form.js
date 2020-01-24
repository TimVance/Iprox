$(function() {

    $('form[name="SIMPLE_FORM_2"]').submit(function(e) {
        e.preventDefault();
        var $button = $(this);

        $button.data('submited', 'Y').attr('disabled','disabled');
        var isError = false;
        var arr = [$('form[name=SIMPLE_FORM_2] input[type=text]') ];



        for(var i = 0; i < arr.length; i++) {
            var el = arr[i];
            if($(el).val() == '') {
                $(el).addClass('field-error');
                isError = true;
            } else {
                $(el).remove('field-error');
            }
        }

        if($('form[name=SIMPLE_FORM_2] textarea').val() == '') {
            $('form[name=SIMPLE_FORM_2] textarea').addClass('field-error');
            isError = true;
        } else {
            $('form[name=SIMPLE_FORM_2] textarea').removeClass('field-error');
        }


        var Data = $(this).serializeJSON();
        Data.web_form_submit = 'Отправить';
        if(!isError) {
            $('.field-error').removeClass('field-error');
            $.ajax({
                async: true,
                data:  Data,
                timeout: 8000,
                type: 'POST',
                url: '/local/templates/morealty/ajax_php/forms.php',
                error: function(jqXHR){
                    console.log('error');
                    $button.data('submited', 'N').removeAttr('disabled');
                    $.colorbox.resize();
                },
                success: function(data){
                    console.log('success');
                    $('form[name=SIMPLE_FORM_1] .but-pop').remove();
                    $('form[name=SIMPLE_FORM_1] .field-pop').remove();
                    $('form[name=SIMPLE_FORM_1] .textarea-pop').html('<span>Ваше сообщение успешно отправлено.</span>');
                    $.colorbox.resize();
                }
            });

            window.location = location.href + '?text=text';
        }
    });
});

$(function() {
    $('form[name="SIMPLE_FORM_3"]').submit(function(e) {
        e.preventDefault();
        var $button = $(this);

        $button.data('submited', 'Y').attr('disabled','disabled');
        var isError = false;
        var arr = [$('form[name=SIMPLE_FORM_3] input[type=text]') ];


        for(var i = 0; i < arr.length; i++) {
            var el = arr[i];
            if($(el).val() == '') {
                $(el).addClass('field-error');
                isError = true;
            } else {
                $(el).remove('field-error');
            }
        }

        if($('form[name=SIMPLE_FORM_3] textarea').val() == '') {
            $('form[name=SIMPLE_FORM_3] textarea').addClass('field-error');
            isError = true;
        } else {
            $('form[name=SIMPLE_FORM_3] textarea').removeClass('field-error');
        }

        var Data = $(this).serializeJSON();
        Data.web_form_submit = 'Отправить';

        if(!isError) {
            $('.field-error').removeClass('field-error');
            $.ajax({
                async: true,
                data:  Data,
                timeout: 8000,
                type: 'POST',
                url: '/local/templates/morealty/ajax_php/forms.php',
                error: function(jqXHR){
                    console.log('error');
                    $button.data('submited', 'N').removeAttr('disabled');
                    $.colorbox.resize();
                },
                success: function(data){
                    console.log('success');
                    $('form[name=SIMPLE_FORM_1] .but-pop').remove();
                    $('form[name=SIMPLE_FORM_1] .field-pop').remove();
                    $('form[name=SIMPLE_FORM_1] .textarea-pop').html('<span>Ваше сообщение успешно отправлено.</span>');
                    $.colorbox.resize();
                }
            });
        }
    });
});
//$('input[name="form_text_6"]').inputmask("+7 (999) 999-99-99");
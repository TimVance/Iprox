$(function() {

    //.ask_question-form_at_builders_page 
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

                if($(el).parents('.field-ask_element').find('.comp-s-new').length <= 0) {
                    $(el).parents('.field-ask_element').append("<span class='comp-s-new comp-s-new-offset'>Заполните обязательное поле «" + $(el).attr('placeholder').replace('*', '').trim() + "»</span>");
                    $.colorbox.resize();
                }
                isError = true;
            } else {
                $(el).removeClass('field-error');
                $(el).parents('.field-ask_element').find('.comp-s-new').remove();
            }
        }

        var textar = $(this).find('textarea');
        if($(this).find('textarea').val() == '') {
            $(textar).addClass('field-error');
            if($(textar).parents('.field-ask_element').find('.comp-s-new').length <= 0) {
                $(textar).parents('.field-ask_element').append("<span class='comp-s-new comp-s-new-offset'>Заполните обязательное поле «" + $(textar).attr('placeholder').replace('*', '').trim() + "»</span>");
            }
            isError = true;
        } else {
            $(textar).removeClass('field-error');
            $(textar).parents('.field-ask_element').find('.comp-s-new').remove();
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
                    $('form[name=SIMPLE_FORM_1] .field-ask').remove();
                    $('form[name=SIMPLE_FORM_1] .textarea-ask').html('<span>Ваше сообщение успешно отправлено.</span>');
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

                if($(el).parents('.field-ask').find('.comp-s-new').length <= 0) {
                    $(el).parents('.field-ask').append("<span class='comp-s-new'>Заполните обязательное поле «" + $(el).attr('placeholder').replace('*', '').trim() + "»</span>");
                    $.colorbox.resize();
                }
                isError = true;
            } else {
                $(el).removeClass('field-error');
                $(el).parents('.field-ask').find('.comp-s-new').remove();
            }
        }


        var textar = $(this).find('textarea');
        if($(this).find('textarea').val() == '') {
            $(textar).addClass('field-error');
            if($(textar).parents('.textarea-pop').find('.comp-s-new').length <= 0) {
                $(textar).parents('.textarea-pop').append("<span class='comp-s-new'>Заполните обязательное поле «" + $(textar).attr('placeholder').replace('*', '').trim() + "»</span>");
            }
            isError = true;
        } else {
            $(textar).removeClass('field-error');
            $(textar).parents('.textarea-pop').find('.comp-s-new').remove();
        }

        var Data = $(this).serializeJSON();
        Data.web_form_submit = 'Отправить';

        console.log('here2');
        if(!isError) {
            console.log('here');
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
                    $('form[name=SIMPLE_FORM_1] .field-ask').remove();
                    $('form[name=SIMPLE_FORM_1] .textarea-ask').html('<span>Ваше сообщение успешно отправлено.</span>');
                    location.reload();
                }
            });
        }
    });
    
    
    
    $("form[name='builder_form']").submit(function(e){
    	var t = $(this);
    	var input = t.find("input[name='search']");
    	var searchVal = input.val();
    	if (!searchVal || searchVal.length <= 0)
    	{
    		e.preventDefault();
    		input.addClass("field-error");
    		var span = input.next("span");
    		if (span.length <= 0 )
    		{
    			input.after('<span class="comp-s-new comp-s-absoluted">Заполните обязательное поле «Название агентства, адрес»</span>');
    		}
    	}
    	else
    	{
    		input.next("span").remove();
    		input.removeClass("field-error");
    	}
    });
});
//$('input[name="form_text_6"]').inputmask("+7 (999) 999-99-99");
$(document).ready(function () {

    /* INCLUDE PLUGINS */

    $("input.phone-mask").mask("+7 999 999 99 99");



    /* BASE FUNCTION */


    $(".js-next-toggle").on( "click", function() {
        $(this).toggleClass('open');
        $(this).next().slideToggle();
        return false;
    });

    $(".js-toggle-id").on( "click", function() {
        $(this).toggleClass('open');
        $('#'+$(this).data('id')).toggle();

        if ( $(this).data('toggle-text') ) {
            txt = $(this).text();
            $(this).text( $(this).data('toggle-text') );
            $(this).data('toggle-text', txt);
        }

    });

    $(".switcher").on( "click", function() {
        $(this).toggleClass('active');
    });


    $(".forms-check input[type=checkbox]").on( "click", function() {
        $(this).parent().toggleClass('active');
    });



    /* drop select */

    $(".drop-list__title").bind("click touchstart", function () {

        if ( $(this).closest('.drop-list').hasClass('open') ) {
            $('.drop-list__dropdown').hide();
            $(this).closest('.drop-list').removeClass('open')
        } else {
            $('.drop-list__dropdown').hide();

            var dropdown = $(this).closest('.drop-list').find('.drop-list__dropdown');
            $(this).closest('.drop-list').toggleClass('open');
            dropdown.show();
            dropdown.css('left', dropdown.width()/2*-1 + $(this).width()/2);
        }

        return false;
    });


    $(".drop-list__dropdown li").bind("click touchstart", function () {
        var title = $(this).closest('.drop-list').removeClass('open').find('.drop-list__title');
        title.html($(this).html());

        $(this).parent().find('.active').removeClass('active');
        $(this).addClass('active');

        $('.drop-list__dropdown').hide();
        return false;
    });


    $("html").bind("click touchstart", function () {
        $('.drop-list__dropdown').fadeOut();
        $('.drop-list').removeClass('open');
    });

    $(".drop-list").bind("click touchstart", function (event) {
        event.stopPropagation();
    });



    /* tabs */

    $('.tabs__header').delegate('.tabs__item:not(.active)', 'click', function () {
        $(this).closest('.tabs__header').find('.active').removeClass('active');
        $(this).addClass('active')
            .parents('.tabs').find('.tabs__box').hide().removeClass('visible').eq($(this).index()).show().addClass('visible');
    });




    $(".text-field:not(.lock)").click(function () {
        $(this).addClass('active');
        $(this).addClass('focus');
        $(this).find('input').focus();
        $(this).find('textarea').focus();
    });

    $(".text-field input, .text-field textarea").focusout(function () {
        if ($(this).val().length == 0 || ~$(this).val().indexOf('(___) ___-__-__') || ($(this).hasClass('phone-mask') && $(this).val().indexOf("_") >= 0)) {
            $(this).closest('.text-field').removeClass('active');
        }

        $(this).closest('.text-field').removeClass('focus');
    });


    $(".search-list__item").click(function () {
        $(this).closest('.text-field').addClass('active').removeClass('focus');
        $(this).closest('.text-field').find('.search-text').val( $(this).text() );

        $(this).parent().find('.active').removeClass('active');
        $(this).addClass('active');

        return false;
    });


    $(".format-money").focus(function () {
        $(this).val( spaceRemove($(this).val()) );
    });

    $(".format-money").focusout(function () {
        $(this).val( numFormat($(this).val(), 'digit') );
    });

});


/* global function */


function numFormat(n, format) {
    if (format == 'digit') {
        if (n > 999) {
            n = String(n).replace(/()(?=(\d{3})+$)/g, '$1 ');
        }
    }

    if (format == 'float') {
        n = parseFloat(n).toFixed(1)
    }

    return n;
}

function spaceRemove(str) {
    return str.replace(/\s/g, '');
}

// Главная страница ипотеки
$(document).ready(function() {
    $(".js-select2").select2();

    var sum = $(".credit-sum");
    var pv = $(".credit-pv");
    var time = $(".credit-time");
    var pay = $(".credit-pay");
    var amount = $(".credit-amount");
    var error = $(".errors");

    var percent = 9.75;

    // Удаление пробелов и преобразование в int
    function delSpaces(str) {
        return parseInt(str.replace(/\s/g, ""));
    }

    function valid() {
        error.text("");
        if (sum.val() == "") {
            sum.focus();
            error.text('Заполните поле "Сумма кредита"');
            return false;
        }
        if (pv.val() == "") {
            pv.focus();
            error.text('Заполните поле "ПВ"');
            return false;
        }
        if (time.val() == "") {
            time.focus();
            error.text('Заполните поле "Срок"');
            return false;
        }
        return true;
    }

    function valid_step2() {
        let step2 = true;
        $(".ipoteka-wrap.step2 input:required").each(function() {
            let el = $(this);
            if(el.val() === '') {
                el.parent().addClass("active focus");
                el.focus();
                step2 = false;
                return false;
            }
        });
        if (step2) return true;
        else return false;
    }

    function valid_step3() {
        let step3 = true;
        $(".ipoteka-wrap.step3 input:required").each(function() {
            let el = $(this);
            if(el.val() === '') {
                el.parent().addClass("active focus");
                el.focus();
                step3 = false;
                return false;
            }
        });
        if (step3) return true;
        else return false;
    }

    // Подсчет стоимость объекта
    $.merge(sum, pv).change(function () {
        if (!valid()) return false;
        amount.val(delSpaces(sum.val()) + delSpaces(pv.val()));
        amount.val( numFormat(amount.val(), 'digit') );
    });

    // Подсчет суммы платежа
    $.merge(sum, time).change(function () {
        if (!valid()) return false;
        var i = percent/100/12;
        var num = i*Math.pow((1+i), delSpaces(time.val()));
        var den = Math.pow((1+i), delSpaces(time.val())) - 1;
        var itog = delSpaces(sum.val()) * (num/den);
        pay.val(Math.round(itog));
        pay.val( numFormat(pay.val(), 'digit') );
    });

    // Подсчет процента ПВ
    $.merge(sum, pv).change(function () {
        if (!valid()) return false;
        var procent = delSpaces(pv.val()) / (delSpaces(sum.val()) * 0.01);
        procent = procent.toFixed(2);
        pv.parent().find(".text-field__info").text(procent + "%");
        amount.val( numFormat(amount.val(), 'digit') );
    });

    // Вывод поле с названием банка
    $("select[name='type']").change(function() {
        var bank = $(".bank-name");
        if ($(this).val() == '570') bank.slideDown();
        else bank.slideUp();
    });

    // Нажатие кнопки первый шаг
    $(".ipoteka-wrap.step1 .button--next").click(function() {
        if (!valid()) return false;
        $(".ipoteka-wrap.step1").slideUp();
        $(".ipoteka-wrap.step2").slideDown();
    });

    $(".ipoteka-wrap.step2 .button--next").click(function() {
        if (!valid_step2()) return false;
        $(".ipoteka-wrap.step2").slideUp();
        $(".ipoteka-wrap.step3").slideDown();
    });

    $(".ipoteka-wrap.step3 .button--next").click(function() {
        if (!valid_step3()) return false;
        $.ajax({
            method: "post",
            url: 'lib/ajax.php',
            data: $(".ipoteka-form").serializeJSON(),
            success: function(result) {
                if(result == "success") {
                    $(".ipoteka-wrap.step3").slideUp();
                    $(".ipoteka-wrap.step4").slideDown();
                }
                else {
                    alert(result);
                }
            }
        });
    });

});
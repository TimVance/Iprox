$(function(){

    $(".goodbuy").click(function(){
        var _t = this,
            gid = $(_t).data('package');

        getResultFromUrl('/local/components/morealty/personal.account/templates/in_personal/ajax/buy.php', {'gid': gid}, function (data) {
            try {
                eval('var d = ' + data + ';');

                if (d.status == 'OK') {
                    $(_t).parents('.pop:first').find('.property-pop').html(d.html);
                    $(_t).hide();
                } else {
                    $(_t).parents('.pop:first').find('.property-pop').html(d.html);
                }
            } catch (i) {
            }
        });
    });

});
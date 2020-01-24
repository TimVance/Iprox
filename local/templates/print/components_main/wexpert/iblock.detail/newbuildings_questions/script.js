$(document).ready(function(){
    $('#fast_search').keyup(function() {
        var names = new Array();
        names = $('.list-search-answ ul li i');
        console.log($(names));
        var val = $(this).val();
        var curVal = '';
        for(var i = 0; i <= names.length ; i++) {
            curVal = ($(names[i])).text().trim().toLowerCase();
            val = val.trim().toLowerCase();
            if(curVal.indexOf(val) + 1) {
                ($(names[i])).parents('li').show();
            } else {
                ($(names[i])).parents('li').hide();
            }
        }
        //console.log($(this).val());
    });
});
$(function() {

    // Редактирование полей
    $(".table > .row > div").click(function() {
        //if(!$(this).hasClass('edit')) $(this).html('<input value="' + $(this).text() + '">').addClass('edit');
    });


    // Удаление фото
    $('.image-wrap .delete_photo').click(function(e) {
        e.preventDefault();
        var agree = confirm("Вы уверены, что хотите удалить фотографию?");
        console.log('click');
        if (agree == true) {
            console.log('Удаление фото');
            var product = $(this).parent().attr("product");
            var photo = $(this).parent().attr("photo");
            console.log(photo);
            $('.delete_form input[name="delete_photo"]').val(photo);
            $('.delete_form input[name="id_product"]').val(product);
            $('.delete_form').submit();
        }
    });


    $('.photos .add-photo').click(function(e) {
        e.preventDefault();
        $(this).parent().find('.upload-photo').click();
        console.log('Добавление фото');
    });
});

document.getElementById("horizontal-scroller")
    .addEventListener('wheel', function(event) {
        if (event.deltaMode == event.DOM_DELTA_PIXEL) {
            var modifier = 1;
            // иные режимы возможны в Firefox
        } else if (event.deltaMode == event.DOM_DELTA_LINE) {
            var modifier = parseInt(getComputedStyle(this).lineHeight);
        } else if (event.deltaMode == event.DOM_DELTA_PAGE) {
            var modifier = this.clientHeight;
        }
        if (event.deltaY != 0) {
            // замена вертикальной прокрутки горизонтальной
            this.scrollLeft += modifier * event.deltaY;
            event.preventDefault();
        }
    });
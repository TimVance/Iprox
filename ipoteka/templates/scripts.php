<!--styles-->
<link rel="stylesheet" href="./css/style.css?v=2">
<link rel="stylesheet" href="./css/select2.min.css?v=1">

<!--js-->
<script src="./js/common.js?v=1"></script>
<script src="./js/jquery.maskedinput.min.js?v=1"></script>
<script src="./js/select2.min.js?v=1"></script>
<script>
    $('input[name="born_date"], input[name="date_of_issue"]').mask('99.99.9999');
    $('input[name="serial_number"]').mask('99-99 999999');
    $('input[name="code_subdivision"]').mask('999-999');
    $('input[name="inn"]').mask('999999999999');
    $('input[name="snils"]').mask('999-999-999 99');
    $(".js-select2").select2();
</script>
$(document).ready(function () {
    $("select.select_type").change(function () {
        $(".type_email").prop('disabled');
    });
});

function AddPayment(id) {
    if ($("#add_payment_form").valid() === false) {
        
        $("#form_error").css("display", "block");
    } else {
        $("#form_error").css("display", "none");
        $("#progressIndicator").css("visibility", "visible");
        var payment_amount = $("#payment_amount").val();
        var payment_date = $("#payment_date").val();
        var is_principal_payment = $("#principal_payment").val();

        var xhr;
        if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            xhr = new XMLHttpRequest();
        } else if (window.ActiveXObject) { // IE 8 and older
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {

                $("#progressIndicator").css("visibility", "hidden");
                if (xhr.response !== 0) {
                    $("#payment_success").css("display", "block");
                } else {
                    // oops, we couldnt find this account. Display an error
                    $("#payment_failure").css("display", "block");
                }

            }
        };

        var data = "id=" + id + "&payment_amount=" + payment_amount + "&payment_date=" + payment_date + "&is_principal_payment=" + is_principal_payment;
        xhr.open("POST", "../app/assets/php/process_add_payment.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(data);
    }
}
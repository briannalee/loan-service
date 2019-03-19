function AddAccount() {
    if ($("#add_account_form").valid() === false) {
        $("#form_error").css("display", "block");
    } else {
        $("#form_error").css("display", "none");
        $("#progressIndicator").css("visibility", "visible");
        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var phone = $("#phone").val();
        var email = $("#email").val();
        var loan_amount = $("#loan_amount").val();
        var loan_rate = $("#loan_rate").val();
        var loan_date = $("#loan_date").val();
        var loan_length = $("#loan_length").val();

        var xhr;
        if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            xhr = new XMLHttpRequest();
        } else if (window.ActiveXObject) { // IE 8 and older
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                $("#progressIndicator").css("visibility", "hidden");
                alert(xhr.response);
                if (xhr.response !== '0') {
                    $("#payment_success").css("display", "block");
                } else {
                    // oops, we couldnt find this account. Display an error
                    $("#payment_failure").css("display", "block");
                    
                }

            }
        };

        var data = "first_name=" + first_name + 
        "&last_name=" + last_name + 
        "&phone=" + phone + 
        "&email=" + email +
        "&loan_amount=" + loan_amount +
        "&loan_rate=" + loan_rate +
        "&loan_date=" + loan_date +
        "&loan_length=" + loan_length;
        xhr.open("POST", "../app/assets/php/process_add_account.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(data);
    }
}
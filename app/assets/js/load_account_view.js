function LoadAccountView(id) {
    $("#progressIndicator").css("visibility", "visible");


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
                alert(xhr.response);
                var data = JSON.parse(xhr.responseText);
                // fill in the data into our display areas
                $("#tableBody").html(data["tableBody"]);
                //("#lastName").text(data["last_name"]);
                $("#firstName").text(data["first_name"]);
                //$("#currentBalance").text(formatMoney(data["currentBalance"]));
                //$("#startingBalance").text(formatMoney(data["startingBalance"]));
                //$("#email").text(data["email"]);
                //$("#phone").text(data["phone"]);
                //$("#lastPayment").text(data["lastpayment"]);
                //$("#accrual").text(formatMoney(data["accrual"]));
                //$("#monthlyPayment").text(formatMoney(data["monthlyPayment"]));
                //$("#startDate").text(data["startDate"]);
                //$("#rate").text(data["rate"]);

                // activate the account detail tab
                activateTab("account_detail");
            } else {
                // oops, we couldnt find this account. Display an error
                md.showNotification('top', 'center', 'Sorry, account not found!', 'danger');
            }

        }
    };

    var data = "id=" + id;
    xhr.open("POST", "../app/assets/php/process_account_view.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
}

function formatMoney(num) {
    return "$" + num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
}

function activateTab(tab) {
    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
}
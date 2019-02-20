$(".login100-form").submit(function(e){
    e.preventDefault();
});


function Login() {
    $("#progressIndicator").css("visibility", "visible");
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var usersTable = 'admin_users';
    var xhr;
    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE 8 and older
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            if (xhr.responseText === 'true') {
                window.location.replace("../index.php");
            }else{
                $("#progressIndicator").css("visibility", "hidden");
                $("#wrongCredentials").css("visibility", "visible");
            }

        }
    }
    var data = "email=" + email;
    data = data + "&password=" + password;
    xhr.open("POST", "../app/assets/php/process_login.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
}
function LoadAccountView(id) {
    $("#progressIndicator").css("visibility", "visible");


    var xhr;
    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE 8 and older
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            $("#progressIndicator").css("visibility", "hidden");
            if (xhr.response !== 0) {
                var data = JSON.parse(xhr.responseText);

                var r = parseFloat(data["rate"]);
                var P = parseFloat(data["loan_amount"]);
                var t = parseInt(data["length"]);
                //var A = P*(1+(r/100)*(t/12));
                var A = calculateAccrual(r,t,P) + P;
                var payment = calculateMonthlyPayment(r,t,P);

                var monthlyPayment = formatMoney(round(payment,2));
                var currentBalance = formatMoney(round(parseFloat(data["current_balance"]),2));
                var startingBalance = formatMoney(round(parseFloat(data["loan_amount"]),2));
                var accrual = formatMoney(round(A,2));
                var startDate = data["startdate"]

                $("#lastName").text(data["last_name"]);
                $("#firstName").text(data["first_name"]);
                $("#currentBalance").text(currentBalance);
                $("#startingBalance").text(startingBalance);
                $("#email").text(data["email"]);
                $("#phone").text(data["phone"]);
                $("#lastPayment").text(data["lastpayment"]);
                $("#accrual").text(accrual);
                $("#monthlyPayment").text(monthlyPayment);
                $("#startDate").text(startDate);


                var tableBody = "";
                var previousBalance = P;
                var alpha = "";
                var i = 1;
                var paymentAmount = payment;
                var unpaidInterest = 0;
                var rowClass = "";


                var paymentIndex = 0;
                var length = 0;
                var isReal = false;
                if (data["payments"] !== 0) {
                    length = data["payments"].length;
                }
                var startDateTimestamp = new Date( data["startdate"]*1000);
                startDateTimestamp = convertDateToUTC(startDateTimestamp);
                while(round(previousBalance,2) > 0.001) {
                    alert("running");
                    var actualPayment = payment;
                    //Are we working with a real payment?
                    if (length > 0 && paymentIndex < length) {
                        isReal = true;
                        actualPayment = parseFloat(data['payments'][paymentIndex]["amount"]);
                        paymentDate = convertDateToUTC(new Date(data['payments'][paymentIndex]["date"]*1000));
                        paymentIndex++;
                        rowClass = "text-light bg-success";
                    }else {
                        paymentDate = addMonths(convertDateToUTC(new Date( data["startdate"]*1000)),i);
                        isReal = false;
                        rowClass = "bg-light";
                    }
                    var leftoverPayment = actualPayment;
                    //Pay off accrued interest first
                    if (unpaidInterest > 0) {
                        leftoverPayment = actualPayment - unpaidInterest;
                        unpaidInterest -= leftoverPayment;
                    }
                    if (unpaidInterest < 0) unpaidInterest = 0;

                    if (leftoverPayment < 0) leftoverPayment = 0;
                    var interestPayment = round(previousBalance * ((r/100)/12),2);
                    var principalPayment = round(leftoverPayment - interestPayment,2);
                    if (principalPayment < 0) principalPayment = 0;
                    if (parseFloat(principalPayment) >  parseFloat(previousBalance)) {
                        paymentAmount = round(payment - (parseFloat(principalPayment) - parseFloat(previousBalance)),2);
                        principalPayment = round(principalPayment - (parseFloat(principalPayment) - parseFloat(previousBalance)),2);
                        alpha = "**";
                        if (!isReal) {
                            actualPayment = paymentAmount;
                        }
                    }


                    if (parseFloat(leftoverPayment) < parseFloat(interestPayment)) {
                        unpaidInterest = unpaidInterest + (interestPayment - actualPayment);
                    }

                    previousBalance = round(previousBalance - principalPayment,2);
                    tableBody = tableBody + "<tr class='" + rowClass + "'><td>" + i + alpha + "</td><td>" + round(paymentAmount,2) + "</td><td>" + round(actualPayment,2) + "</td><td>"+ interestPayment +"</td><td>" + principalPayment + "</td><td>"+ previousBalance +"</td><td>" + round(unpaidInterest,2) + "</td><td>"+ paymentDate + "</td></tr>";
                    i++;

                    if (i>1000) break;
                }

                $("#tableBody").html(tableBody);
                alert(tableBody);
            }
            else {
                md.showNotification('top','center','Sorry, account not found!','danger');
            }

        }
    };

    var data = "id=" + id;
    xhr.open("POST", "../app/process_account_view.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
    $("#firstName").text = "Hiii";


}



function calculateMonthlyPayment(interestRate,loanTerm,principalAmount) {
    var i = interestRate/100.0/12.0
    var tau = 1.0 + i
    var tauToTheN = Math.pow(tau, loanTerm ) ;
    var magicNumber = tauToTheN * i / (tauToTheN - 1.0 )
    return principalAmount * magicNumber
}


function calculateAccrual(interestRate,loanTerm,principalAmount) {
    var i = interestRate/100.0/12.0
    var tau = 1.0 + i
    var tauToTheN = Math.pow(tau, loanTerm ) ;
    var magicNumber = tauToTheN * i / (tauToTheN - 1.0 )
    return principalAmount * magicNumber * loanTerm - principalAmount
}


function formatMoney(num) {
    return "$" + num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
}

function round(value, decimals) {
    return Number(Math.round(value +'e'+ decimals) +'e-'+ decimals).toFixed(decimals);
}

function createDateAsUTC(date) {
    return new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(), date.getSeconds()));
}

function convertDateToUTC(date) {
    return new Date(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds());
}

function addMonths(date, months) {
    var d = date.getDate();
    date.setMonth(date.getMonth() + +months);
    if (date.getDate() != d) {
        date.setDate(0);
    }
    return date;
}
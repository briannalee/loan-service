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
                // receive data from mysql
                var data = JSON.parse(xhr.responseText);

                // parse the needed variables into floats/ints 
                // do maths as needed to calculate payment amount, accrual, etc
                var r = parseFloat(data["rate"]); // rate
                var P = parseFloat(data["loan_amount"]); // principal
                var t = parseInt(data["length"]); // loan length in months
                var A = calculateAccrual(r, t, P) + P; // accrual
                var payment = calculateMonthlyPayment(r, t, P); // monthly payment
                var startDateTimestamp = data["loan_start_date"]; // loan start date
                var startDate = new Date(startDateTimestamp * 1000); // convert loan start date to something we can use


                // format our numbers into pretty display formats    
                startDate = convertDateToUTC(startDate); // convert the loan start date to a useable date
                var monthlyPayment = formatMoney(round(payment, 2));
                var currentBalance = formatMoney(round(parseFloat(data["current_balance"]), 2));
                var startingBalance = formatMoney(round(parseFloat(data["loan_amount"]), 2));
                var accrual = formatMoney(round(A, 2));

                // init a few variables for use in our calculations below
                var tableBody = ""; // where our amortization table will go
                var previousBalance = P; // var to store the remaining principal
                var superscript = ""; // var to denote a special payemnt (adjusted, future, etc)
                var paymentNumber = 1; // number of payment, predicted or actual payment
                var paymentAmount = payment; // var to store the payment amount made
                var unpaidInterest = 0; // var to store the total amount of unpaid interest
                var rowStyleClass = ""; // var to denote the style class used for this row
                var paymentIndex = 0; // if working with an actual payment, the index of the actual payment
                var actualPaymentLength = 0; // length of the actual payment array
                var isActualPayment = false; // is this an actual payment, or predicted payment
                var paymentDate = 0;

                // check if we have any actual payments to process 
                if (data["payments"] !== 0) {
                    actualPaymentLength = data["payments"].length;
                }


                while (round(previousBalance, 2) > 0.001) {
                    // suggest payment is regular payment + any unpaid accrued interest
                    paymentAmount = payment + unpaidInterest;
                    var actualPayment = payment;
                    //Are we working with a real payment?
                    if (actualPaymentLength > 0 && paymentIndex < actualPaymentLength) {
                        isActualPayment = true;
                        actualPayment = parseFloat(data['payments'][paymentIndex]["payment_amount"]);
                        paymentDate = convertDateToUTC(new Date(data['payments'][paymentIndex]["date"] * 1000));
                        paymentIndex++;
                        rowStyleClass = "text-light bg-success";
                    } else {
                        paymentDate = addMonths(convertDateToUTC(new Date(data["loan_start_date"] * 1000)), paymentNumber);
                        isActualPayment = false;
                        rowStyleClass = "bg-light text-secondary";
                        actualPayment = paymentAmount;
                    }

                    // has the payment been missed?
                    if (paymentDate < Date.now() && !isActualPayment) {
                        actualPayment = 0;
                        rowStyleClass = "bg-danger text-light";
                    }

                    var leftoverPayment = actualPayment;
                    //Pay off accrued interest first
                    if (unpaidInterest > 0) {
                        leftoverPayment = actualPayment - unpaidInterest;
                        if (unpaidInterest > actualPayment) {
                            leftoverPayment = 0;
                            unpaidInterest -= actualPayment;
                        }else{
                            unpaidInterest -= actualPayment;
                        }
                    }
                    if (unpaidInterest < 0)
                        unpaidInterest = 0;

                    var interestPayment = round(previousBalance * ((r / 100) / 12), 2);
                    var principalPayment = round(leftoverPayment - interestPayment, 2);

                    if (principalPayment < 0) {
                        principalPayment = 0;
                    }
                    if (parseFloat(principalPayment) > parseFloat(previousBalance)) {
                        paymentAmount = round(payment - (parseFloat(principalPayment) - parseFloat(previousBalance)), 2);
                        principalPayment = round(principalPayment - (parseFloat(principalPayment) - parseFloat(previousBalance)), 2);
                        superscript = "**";
                        if (!isActualPayment) {
                            actualPayment = paymentAmount;
                        }
                    }

                    previousBalance = round(previousBalance - principalPayment, 2);

                    // how much did we actually pay in interest?
                    var interestPaid = actualPayment - principalPayment;
                    if (interestPaid < 0)
                        interestPaid = 0;


                    if (parseFloat(leftoverPayment) < parseFloat(interestPayment)) {
                        alert("Prevoius Interest: " + unpaidInterest + " | Adding: " + (interestPayment - actualPayment))
                        unpaidInterest = unpaidInterest + (interestPayment - actualPayment);
                    }

                    tableBody = tableBody + "<tr class='" + rowStyleClass + "'><td>" +
                            paymentNumber + superscript + "</td><td>" +
                            round(paymentAmount, 2) + "</td><td>" +
                            round(actualPayment, 2) + "</td><td>" +
                            round(interestPayment, 2) + "</td><td>" +
                            round(interestPaid, 2) + "</td><td>" +
                            principalPayment + "</td><td>" +
                            previousBalance + "</td><td>" +
                            round(unpaidInterest, 2) + "</td><td>" +
                            paymentDate + "</td></tr>";
                    paymentNumber++;

                    if (paymentNumber > 1000)
                        break;
                }

                // fill in the data into our display areas
                $("#tableBody").html(tableBody);
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

                // activate the account detail tab
                activateTab("account_detail")
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



function calculateMonthlyPayment(interestRate, loanTerm, principalAmount) {
    var i = interestRate / 100.0 / 12.0;
    var tau = 1.0 + i;
    var tauToTheN = Math.pow(tau, loanTerm);
    var magicNumber = tauToTheN * i / (tauToTheN - 1.0);
    return principalAmount * magicNumber;
}


function calculateAccrual(interestRate, loanTerm, principalAmount) {
    var i = interestRate / 100.0 / 12.0;
    var tau = 1.0 + i;
    var tauToTheN = Math.pow(tau, loanTerm);
    var magicNumber = tauToTheN * i / (tauToTheN - 1.0);
    return principalAmount * magicNumber * loanTerm - principalAmount;
}


function formatMoney(num) {
    return "$" + num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
}

function round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals).toFixed(decimals);
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
    if (date.getDate() !== d) {
        date.setDate(0);
    }
    return date;
}

function activateTab(tab) {
    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
}
;
<?php

namespace LoanService;
use \Datetime;

session_start();

// autoload classes
include 'autoloader.php';

// Which account id are we looking up
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

$account = new User(1);
echo $account->get_loan_amount();
echo $account->get_first_name();

die();

// initiate db
$db_connection = Database::connect();

// pull the account and user info
$query = "SELECT * FROM accounts,users WHERE accounts.id='$id' AND users.account_id='$id'";
$account_result = mysqli_query($db_connection, $query);
$account_info = mysqli_fetch_assoc($account_result);

if ($account_info) {
    // Grab all payments, if any
    $query = "SELECT * FROM payments WHERE account_id='$id'";
    $payment_result = mysqli_query($db_connection, $query);
    $payment_index = 0;
    while ($payment_info = mysqli_fetch_assoc($payment_result)) {
        //There is payments, add to array
        $payment_info['date'] = strtotime($payment_info['date'] . " UTC");
        $account_info['payments'][$payment_index] = $payment_info;
        $payment_index++;
    }
    if ($payment_index < 1) {
        $account_info["payments"] = 0;
    }

    $account_info['loan_start_date'] = strtotime($account_info['loan_start_date'] . " UTC");
    $returnData = calculateAmort($account_info);
    $returnData["last_name"] = $account_info["last_name"];
    $returnData["first_name"] = $account_info["first_name"];
    $returnData["currentBalance"] = round($account_info["loan_amount"],2);
    $returnData["email"] = $account_info["email"];
    $returnData["phone"] = $account_info["phone"];
    $returnData["lastpayment"] = $account_info["loan_start_date"];
    $returnData["startDate"] = $account_info["loan_start_date"];

    echo json_encode($returnData);
} else {
    // Account not found
    echo 0;
}

// Free result set
mysqli_free_result($account_result);

mysqli_close($db_connection);

function calculateAmort($data) {
    $returnData[] = [];
    // parse the needed variables into floats/ints
    // do maths as needed to calculate payment amount, accrual, etc
    $r = $data["rate"]; // rate
    $P = $data["loan_amount"]; // principal
    $t = $data["length"]; // loan length in months
    $A = calculateAccrual($r, $t, $P) + $P; // accrual
    $payment = calculateMonthlyPayment($r, $t, $P); // monthly payment
    $startDate = new DateTime(); 
    $startDate->setTimestamp($data["loan_start_date"]);
    // init a few variables for use in our calculations below
    $tableBody = ""; // where our amortization table will go
    $previousBalance = $P; // var to store the remaining principal
    $superscript = ""; // var to denote a special payemnt (adjusted, future, etc)
    $paymentNumber = 1; // number of payment, predicted or actual payment
    $paymentAmount = $payment; // var to store the payment amount made
    $unpaidInterest = 0; // var to store the total amount of unpaid interest
    $rowStyleClass = ""; // var to denote the style class used for this row
    $paymentIndex = 0; // if working with an actual payment, the index of the actual payment
    $actualPaymentLength = 0; // length of the actual payment array
    $isActualPayment = false; // is this an actual payment, or predicted payment
    $paymentDate = 0;

    // check if we have any actual payments to process 
    if ($data["payments"] !== 0) {
        $actualPaymentLength = sizeof($data["payments"]);
    }

    while (round($previousBalance, 2) > 0.001) {
        // suggest payment is regular payment + any unpaid accrued interest
        $paymentAmount = $payment + $unpaidInterest;
        $actualPayment = $payment;
        // Are we working with a real payment?
        if ($actualPaymentLength > 0 && $paymentIndex < $actualPaymentLength) {
            // is actual payment
            $isActualPayment = true;
            $actualPayment = $data['payments'][$paymentIndex]["payment_amount"];
            $paymentDate = new DateTime();
            $paymentDate->setTimestamp($data['payments'][$paymentIndex]["date"]);
            $paymentIndex++;
            $rowStyleClass = "text-light bg-success";
        } else {
            $paymentDate = addMonths($startDate, $paymentNumber);
            $isActualPayment = false;
            $rowStyleClass = "bg-light text-secondary";
            $actualPayment = $paymentAmount;
        }

        // has the payment been missed?
        if ($paymentDate < new DateTime() && !$isActualPayment) {
            $actualPayment = 0;
            $rowStyleClass = "bg-danger text-light";
        }

        // how much payment is left after we pay off accrued interest?
        $leftoverPayment = $actualPayment;
        //Pay off accrued interest first, if any
        if ($unpaidInterest > 0) {
            $leftoverPayment = $actualPayment - $unpaidInterest;
            //is our accrued interest higher than the payment amount?
            if ($unpaidInterest > $actualPayment) {
                $leftoverPayment = 0; // yes, there is no leftover payment
            }
            $unpaidInterest -= $actualPayment; // subtract what was paid from accrued interest
            // if the actual payment was higher than interest, reset unpaid interest to zero
            $unpaidInterest = ($unpaidInterest < 0 ? 0 : $unpaidInterest);
        }

        // calculate our interest payment & principal payment
        $interestPayment = round($previousBalance * (($r / 100) / 12), 2);
        $principalPayment = round($leftoverPayment - $interestPayment, 2);
        $principalPayment = ($principalPayment < 0 ? 0 : $principalPayment); // our principal payment is never less than 0
        
        // is our payment amount higher than what we owe?
        if ($principalPayment > $previousBalance) {
            // yes, we need to reduce our payment amount to match
            //remove the excess principal payment, but keep interest payment the same
            $paymentAmount = round($payment - ($principalPayment - $previousBalance), 2);
            $principalPayment = round($principalPayment - ($principalPayment - $previousBalance), 2);
            $superscript = "**"; //this is an adjusted payment, so denote that to the user
            // if it's not an actual payment, we will use this adjusted suggested payment
            $actualPayment = ($isActualPayment ? $actualPayment : $paymentAmount);
        }

        // finally, subtract the principal payment from the principal
        $previousBalance = round($previousBalance - $principalPayment, 2);

        // how much did we actually pay in interest?
        // including unpaid interest paid, and regular interest paid
        $interestPaid = $actualPayment - $principalPayment;
        $interestPaid = ($interestPaid < 0 ? 0 : $interestPaid);

        // did the customer pay less than the interest due?
        if ($leftoverPayment < $interestPayment) {
            // add unpaid interest amount to the unpaid interest amount
            $unpaidInterest = $unpaidInterest + ($interestPayment - $actualPayment);
            if ($isActualPayment) {
                $rowStyleClass = "text-dark bg-warning";
            }
        }

        // add this row to the table
        $tableBody = $tableBody . "<tr class='" . $rowStyleClass . "'><td>" .
                $paymentNumber . $superscript . "</td><td>" .
                round($paymentAmount, 2) . "</td><td>" .
                round($actualPayment, 2) . "</td><td>" .
                round($interestPayment, 2) . "</td><td>" .
                round($interestPaid, 2) . "</td><td>" .
                $principalPayment . "</td><td>" .
                $previousBalance . "</td><td>" .
                round($unpaidInterest, 2) . "</td><td>" .
                $paymentDate->format("m-d-Y") . "</td></tr>";

        $paymentNumber++; // next payment
    }
    $returnData['tableBody'] = $tableBody;
    $returnData['rate'] = $r;
    $returnData['accrual'] = round($A,2);
    $returnData['monthlyPayment'] = round($payment,2);
    $returnData['length'] = $t;
    $returnData['startingBalance'] = $P;
    return $returnData;
}

function calculateMonthlyPayment($interestRate, $loanTerm, $principalAmount) {
    $i = $interestRate / 100.0 / 12.0;
    $tau = 1.0 + $i;
    $tauToTheN = pow($tau, $loanTerm);
    $magicNumber = $tauToTheN * $i / ($tauToTheN - 1.0);
    return $principalAmount * $magicNumber;
}

function calculateAccrual($interestRate, $loanTerm, $principalAmount) {
    $i = $interestRate / 100.0 / 12.0;
    $tau = 1.0 + $i;
    $tauToTheN = pow($tau, $loanTerm);
    $magicNumber = $tauToTheN * $i / ($tauToTheN - 1.0);
    return $principalAmount * $magicNumber * $loanTerm - $principalAmount;
}

function addMonths($dt, $months) {
    $date = new DateTime($dt->format("Y-m-d"));
    $day = $date->format('j');
    $date->modify("first day of +$months month");
    $date->modify('+' . (min($day, $date->format('t')) - 1) . ' days');

    return $date;
}
<?php

namespace LoanService;

// autoload classes
include 'autoloader.php';

// Which account id are we looking up
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

// Create user class
$account = new User($id);

// Get Payment array
$payments = $account->get_amortization()->get_payment_array($account);

// Payment index, for display purposes
$payment_index = 1;

// Array we will pass back to the AJAX function that called this script
$return_data = [];

// Table containing the payments
$tableBody = 0;


/* * ********************************************************************
 * BEGIN RETURN DATA
 * ******************************************************************** */

$return_data['first_name'] = $account->get_first_name();
$return_data['last_name'] = $account->get_last_name();
$return_data['starting_balance'] = $account->get_loan_principal();
$return_data['current_balance'] = $account->get_current_balance();
$return_data['email'] = $account->get_email();
$return_data['phone'] = $account->get_phone();
$return_data['last_payment'] = $account->get_last_payment()->format("m-d-Y");
$return_data['accrual'] = round($account->get_loan_accrual(), 2);
$return_data['monthly_payment'] = round($account->get_loan_monthly_payment(), 2);
$return_data['start_date'] = $account->get_loan_start_date()->format("m-d-Y");
$return_data['rate'] = $account->get_loan_rate();
$return_data['missed_payments'] = $account->get_missed_payments();
$return_data['late_charges'] = $account->get_current_missed_payments() * $account->get_late_charge();

foreach ($payments as $payment) {

    $tableBody = $tableBody .
            "<tr class='" . $payment->get_payment_class() . "'><td>" .
            $payment_index . "</td><td>" .
            round($payment->get_desired_payment(), 2) . "</td><td>" .
            round($payment->get_payment_amount(), 2) . "</td><td>" .
            round($payment->get_desired_interest(), 2) . "</td><td>" .
            round($payment->get_payment_interest(), 2) . "</td><td>" .
            round($payment->get_payment_principal(), 2) . "</td><td>" .
            round($payment->get_ending_principal(), 2) . "</td><td>" .
            $payment->get_due_date()->format("m-d-Y") . "</td><td>" .
            $payment->get_payment_date()->format("m-d-Y") . "</td></tr>";

    $payment_index++;
}

$return_data['tableBody'] = $tableBody;

/**********************************************************************
* END RETURN DATA
**********************************************************************/

// Send the data back to our AJAX script
echo json_encode($return_data);

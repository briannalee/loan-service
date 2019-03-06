<?php

namespace LoanService;

// autoload classes
include 'autoloader.php';

// Which account id are we looking up
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
$payment_amount = filter_input(INPUT_POST, 'payment_amount', FILTER_SANITIZE_SPECIAL_CHARS);
$payment_date = filter_input(INPUT_POST, 'payment_date', FILTER_SANITIZE_SPECIAL_CHARS);
$is_principal_payment = filter_input(INPUT_POST, 'is_principal_payment', FILTER_SANITIZE_SPECIAL_CHARS);

$db_connection = Database::connect();

// pull the account and user info
$query = "INSERT INTO payments (date, account_id, payment_amount, is_principal_payment)
VALUES ('$payment_date', '$id', '$payment_amount', '$is_principal_payment')";
$payment_result = mysqli_query($db_connection, $query);

if ($payment_result) {
    echo 1;
} else {
    echo 0;
}

mysqli_close($db_connection);

<?php

namespace LoanService;

// autoload classes
include 'autoloader.php';

// Which account id are we looking up
$first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS);
$last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$loan_date = filter_input(INPUT_POST, 'loan_date', FILTER_SANITIZE_SPECIAL_CHARS);
$loan_amount = filter_input(INPUT_POST, 'loan_amount', FILTER_SANITIZE_SPECIAL_CHARS);
$loan_rate = filter_input(INPUT_POST, 'loan_rate', FILTER_SANITIZE_SPECIAL_CHARS);
$loan_length = filter_input(INPUT_POST, 'loan_length', FILTER_SANITIZE_SPECIAL_CHARS);

$db_connection = Database::connect();
// pull the account and user info
$query = "INSERT INTO accounts (loan_amount, length, rate, loan_start_date)
VALUES ('$loan_amount', '$loan_length', '$loan_rate', '$loan_date')";
$account_result = mysqli_query($db_connection, $query);

echo mysqli_error($db_connection);

$user_query = "INSERT INTO users (account_id, first_name, last_name, email, phone)
VALUES (LAST_INSERT_ID(), '$first_name', '$last_name', '$email','$phone')";
$user_result = mysqli_query($db_connection, $user_query);
echo mysqli_error($db_connection);
if ($account_result && $user_result) {
    echo 1;
} else {
    echo 0;
}

mysqli_close($db_connection);

<?php

namespace LoanService;

session_start();

require_once 'autoloader.php';

$email = $_POST['email'];
$password = $_POST['password'];
$userTable = 'admin_users';

$loginResult = Auth::login($email,$password, $userTable);

echo json_encode($loginResult ? true : false);
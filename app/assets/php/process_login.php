<?php
/**
 * Created by PhpStorm.
 * User: Brianna
 * Date: 1/16/2019
 * Time: 11:07 AM
 */
session_start();
spl_autoload_register(function ($class) {
    include $class . '.class.php';
});

$email = $_POST['email'];
$password = $_POST['password'];
$userTable = 'admin_users';

$loginResult = Auth::login($email,$password, $userTable);

echo json_encode($loginResult ? true : false);
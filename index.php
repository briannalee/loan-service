<?php
session_start();

spl_autoload_register(function ($class) {
    include 'app/assets/php/' . $class . '.class.php';
});

// login path and home path are passed to the auth script
Auth::authenticate('/loan-service/loan-service/login/','admin/dash');

$page = 'dash';
if(isset($_GET['id'])) {
    $page = $_GET['id'];
}
include('app/header.php');
include('app/' . $page . '.php');
include('app/footer.php');

?>

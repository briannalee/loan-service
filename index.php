<?php
session_start();
include("app/authentication.php");

$page = 'dash';
if(isset($_GET['id'])) {
    $page = $_GET['id'];
}
include('app/header.php');
include('app/' . $page . '.php');
include('app/footer.php');

?>

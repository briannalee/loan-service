<?php
/**
 * Created by PhpStorm.
 * User: Brianna
 * Date: 1/16/2019
 * Time: 11:07 AM
 */
session_start();
$email = $_POST['email'];
$password = $_POST['password'];

include("database_connect.php");
$dbConnection = InitiateDB();

$query="SELECT * FROM admin_users WHERE email='$email'";
$result=mysqli_query($dbConnection,$query);

if($row = mysqli_fetch_assoc($result)) {

    if (password_verify($password,$row['password'])) {
        $_SESSION['login_id'] = $row['id'];

        echo 1;

    }else{
        echo 0;
    }
}else{
    echo 0;
}

// Free result set
mysqli_free_result($result);

mysqli_close($dbConnection);

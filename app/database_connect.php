<?php
/**
 * Created by PhpStorm.
 * User: Brianna
 * Date: 1/16/2019
 * Time: 2:18 AM
 */

function InitiateDB() {
    $servername = 'localhost';
    $username = 'database_user';
    $password = 'Yba4kXS8h$Ip#19D89$5%Wd1AM4kl!2zmzo2';
    $database = 'loan-service';
    return DbConnect($servername,$username,$password, $database);
        
}

/**
 * @param $servername
 * @param $username
 * @param $password
 * @return mysqli
 */
function DbConnect($servername, $username, $password, $database) {
    $conn =  mysqli_connect($servername, $username, $password, $database);
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    return $conn;
}

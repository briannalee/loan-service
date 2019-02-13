<?php
/**
 * Created by PhpStorm.
 * User: Brianna
 * Date: 1/16/2019
 * Time: 10:31 AM
 */
if(isset($_SESSION['login_id']) && $_SESSION['login_id'] != null) {
    if(!isset($_GET['id'])) {
        header("Location: admin/dash");
        die();
    }
}else{
    header("Location: login/");
    die();
}
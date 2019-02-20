<?php
/**
 * Created by PhpStorm.
 * User: Brianna
 * Date: 1/16/2019
 * Time: 11:07 AM
 */
session_start();
session_unset();     // unset $_SESSION variable for the run-time 
session_destroy();   // destroy session data in storage
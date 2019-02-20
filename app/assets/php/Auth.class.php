<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of auth
 *
 * @author brianna
 */
class Auth {

    /**
     * Authenticates the user
     */
    public function authenticate($loginPath,$homePath) {
        // first check if the session has expired
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            // last request was more than 30 minutes ago
            session_unset();     // unset $_SESSION variable for the run-time 
            session_destroy();   // destroy session data in storage
        }
        $_SESSION['last_activity'] = time(); // update last activity time stamp
        // check if the user is logged in
        if (isset($_SESSION['login_id']) && $_SESSION['login_id'] != null) {
            // user is logged in, check if they need to be redirected to the home page
            // if page id is set, no redirect needed, allow user to proceed to page, end of auth procedure
            if (!isset($_GET['id'])) {
                header("Location: $homePath"); // redirect to the home page
                die(); // kill process in case header redirect didn't work
            }
        } else {
            // user is not logged in, send back to login
            header("Location: $loginPath");
            die(); // kill process in case header redirect didn't work
        }
    }

    public function login($email,$password, $userTable) {

        $dbConnection = Database::connect();

        $query = "SELECT * FROM $userTable WHERE email='$email'";
        $result = mysqli_query($dbConnection, $query);

        if ($row = mysqli_fetch_assoc($result)) {

            if (password_verify($password, $row['password'])) {
                $_SESSION['login_id'] = $row['id'];

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

// Free result set
        mysqli_free_result($result);

        mysqli_close($dbConnection);
    }

}

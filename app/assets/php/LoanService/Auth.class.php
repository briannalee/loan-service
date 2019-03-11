<?php

namespace LoanService;

/**
 * Provides user authentication
 *
 * @author brianna
 */
class Auth {
    
    /**
     * authenticate
     * 
     * Authenticates that the user is logged in, and session is not expired
     * 
     * @param string $loginPath URL to the login page
     * @param string $homePath URL to the home page
     * @param int $timeout Session timeout in seconds, default 30 min
     */
    public static function authenticate($loginPath,$homePath, $timeout = 1800) {
        // first check if the session has expired
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
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

    /**
     * Logs in a user with the specified credentials, looking up from specified
     * table
     * @param type $email
     * @param type $password
     * @param type $userTable
     * @return boolean
     */
    public static function login($email,$password, $userTable) {

        $dbConnection = Database::connect();

        $query = "SELECT * FROM $userTable WHERE email='$email'";
        $result = mysqli_query($dbConnection, $query);

        if ($row = mysqli_fetch_assoc($result)) {

            if (password_verify($password, $row['password'])) {
                $_SESSION['login_id'] = $row['id'];
                $_SESSION['last_name'] = $row['last_name'];
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

<?php

namespace LoanService;

/**
 * Provides user information
 *
 * @author brianna
 */
class User extends Account {
    
    private $first_name;
    private $last_name;
    private $email;
    private $phone;
    private $account_id;
    
    /**
     * Pulls the user info from the database
     * 
     * @param int $id account ID of the account we're looking up
     */
    public function __construct($id) {
        // Connect to the database
        $db_connection = Database::connect();
        
        // Pull the user info
        $query = "SELECT * FROM users WHERE account_id='$id'";
        $user_result = mysqli_query($db_connection, $query);
        $user_info = mysqli_fetch_assoc($user_result);

        // assign to our class variables
        if ($user_info) {
            $this->first_name = $user_info['first_name'];
            $this->last_name = $user_info['last_name'];
            $this->email = $user_info['email'];
            $this->phone = $user_info['phone'];
            $this->account_id = $user_info['account_id'];
        } else {
            echo "Error! User not found";
        }
        
        // Now pull the account information
        parent::__construct($this->account_id);
        
        // Close our connections and free result
        mysqli_free_result($user_result);
        mysqli_close($db_connection);
    }
    
    /**
     * Returns the first name on the account
     * @return string
     */
    public function get_first_name() {
        return $this->first_name;
    }
    
    /**
     * Returns the last name on the account
     * @return string
     */
    public function get_last_name() {
        return $this->last_name;
    }
    
    /**
     * Returns the email on the account
     * @return string
     */
    public function get_email() {
        return $this->email;
    }
    
    /**
     * Returns the phone on the account
     * @return string
     */
    public function get_phone() {
        return $this->phone;
    }
    
    /**
     * Returns the account id on the account
     * @return string
     */
    public function get_account_id() {
        return $this->account_id;
    }
}

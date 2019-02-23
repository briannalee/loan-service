<?php

namespace LoanService;

/**
 * Provides account information and amortization tables
 *
 * @author brianna
 */
class Account {
    
    private $loan_amount;
    private $loan_rate;
    private $loan_length;
    private $loan_monthly_payment;
    private $loan_accrual;
    private $loan_last_payment;
    private $loan_start_date;

    /**
     * 
     * @todo Error handling for invalid account id
     * @param type $id
     */
    public function __construct($id) {
        $db_connection = Database::connect();

        // pull the account and user info
        $query = "SELECT * FROM accounts WHERE id='$id'";
        $account_result = mysqli_query($db_connection, $query);
        
        $account_info = mysqli_fetch_assoc($account_result);

        if ($account_info) {
            $this->loan_amount = $account_info['loan_amount'];
            $this->loan_rate = $account_info['rate'];
            $this->loan_length = $account_info['length'];
            $this->loan_start_date = $account_info['loan_start_date'];
        } else {
            echo "Error! Account not found: $id";
        }
        
        mysqli_free_result($account_result);
        mysqli_close($db_connection);
    }
    
    public function get_loan_amount() {
        return $this->loan_amount;
    }
    
    public function get_loan_rate() {
        return $this->loan_rate;
    }
    
    public function get_loan_length() {
        return $this->loan_length;
    }
    
    public function get_loan_monthly_payment() {
        return $this->loan_monthly_payment;
    }
    
    public function get_loan_accrual() {
        return $this->loan_accrual;
    }
    
    public function get_last_payment() {
        return $this->loan_last_payment;
    }
    
    public function get_loan_start_date() {
        return $this->loan_start_date;
    }

}

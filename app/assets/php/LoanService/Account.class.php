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
    private $loan

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
        } else {
            echo "Error! Account not found";
        }
        
        mysqli_free_result($account_result);
        mysqli_close($db_connection);
    }
    
    public function get_loan_amount() {
        return $this->loan_amount;
    }

}

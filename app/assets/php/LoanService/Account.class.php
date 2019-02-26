<?php

namespace LoanService;

use \DateTime;

/**
 * Provides account information and amortization tables
 *
 * @author brianna
 */
class Account {

    private $loan_principal;
    private $loan_rate;
    private $loan_term;
    private $loan_monthly_payment;
    private $loan_accrual;
    private $loan_last_payment;
    private $loan_start_date;
    private $account_id;
    private $amortization;
    private $missed_payments;
    private $current_missed_payments;
    
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
            $this->loan_principal = $account_info['loan_amount'];
            $this->loan_rate = $account_info['rate'];
            $this->loan_term = $account_info['length'];
            $this->loan_start_date = new DateTime();
            $this->loan_start_date->setTimestamp(strtotime($account_info['loan_start_date'] . " UTC"));
            $this->loan_monthly_payment = $this->calculate_monthly_payment($this->loan_rate, $this->loan_term, $this->loan_principal);
            $this->loan_accrual = $this->calculate_accrual($this->loan_rate, $this->loan_term, $this->loan_principal);
            $this->account_id = $id;
            $this->amortization = new Amortization($this);
        } else {
            echo "Error! Account not found: $id";
        }

        mysqli_free_result($account_result);
        mysqli_close($db_connection);
    }

    public function get_loan_principal() {
        return $this->loan_principal;
    }

    public function get_loan_rate() {
        return $this->loan_rate;
    }

    public function get_loan_term() {
        return $this->loan_term;
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

    public function get_loan_payment() {
        return $this->loan_monthly_payment;
    }
    
    public function get_account_id() {
        return $this->account_id;
    }
    
    public function get_missed_payments() {
        return $this->missed_payments;
    }
    
    public function get_current_missed_payments() {
        return $this->current_missed_payments;
    }
    
    public function add_missed_payment() {
        $this->current_missed_payments++;
        $this->missed_payments++;
    }
    
    public function remove_missed_payment() {
        $this->current_missed_payments--;
    }
    
    public function get_amortization() : Amortization {
        return $this->amortization;
    }

    /**
     * Returns the date the loan was created
     * @return DateTime
     */
    public function get_loan_start_date() {
        return $this->loan_start_date;
    }

    public function get_payment_date($payment_number) {
        return $this->add_months($this->loan_start_date, $payment_number);
    }

    private function calculate_monthly_payment($interestRate, $loanTerm, $principalAmount) {
        $i = $interestRate / 100.0 / 12.0;
        $tau = 1.0 + $i;
        $tauToTheN = pow($tau, $loanTerm);
        $magicNumber = $tauToTheN * $i / ($tauToTheN - 1.0);
        return $principalAmount * $magicNumber;
    }

    private function calculate_accrual($interestRate, $loanTerm, $principalAmount) {
        $i = $interestRate / 100.0 / 12.0;
        $tau = 1.0 + $i;
        $tauToTheN = pow($tau, $loanTerm);
        $magicNumber = $tauToTheN * $i / ($tauToTheN - 1.0);
        return $principalAmount * $magicNumber * $loanTerm - $principalAmount;
    }

    private function add_months($dt, $months) {
        $date = new DateTime($dt->format("Y-m-d"));
        $day = $date->format('j');
        $date->modify("first day of +$months month");
        $date->modify('+' . (min($day, $date->format('t')) - 1) . ' days');

        return $date;
    }
}

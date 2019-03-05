<?php

namespace LoanService;

use DateTime;

/**
 * Description of Payment
 *
 * @author brianna
 */
class Payment {

    /**********************************************************************
     * BEGIN INTERNAL CLASS VARIABLES
     **********************************************************************/
    
    private $payment_date;
    private $payment_amount;
    private $payment_towards_interest = null;
    private $payment_towards_principal = null;
    private $ending_principal = null;
    private $desired_interest_payment = null;
    private $account;
    private $payment_class = 0;
    private $is_principal_payment = 0;
    private $desired_payment;
    private $due_date;
    
    /**********************************************************************
     * END INTERNAL CLASS VARIABLES
     **********************************************************************/

    /**
     * Class constructor
     * 
     * @param float $amount Payment Amount
     * @param DateTime $date Payment Date
     * @param \LoanService\Account $account Account class this payment is for
     */
    public function __construct(float $amount, DateTime $date, Account $account) {
        $this->payment_amount = $amount;
        $this->payment_date = $date;
        $this->account = $account;
        $this->desired_payment = $account->get_loan_payment();
        $this->due_date = $date;
    }

    
    /**********************************************************************
     * BEGIN ACCESSOR METHODS
     **********************************************************************/
    
    public function get_payment_date() {
        return $this->payment_date;
    }
    
    public function get_due_date() {
        return $this->due_date;
    }
    
    public function set_due_date(DateTime $due_date) {
        $this->due_date = $due_date;
    }

    public function get_desired_payment() {
        return $this->desired_payment;
    }

    public function get_payment_amount() {
        return $this->payment_amount;
    }

    public function get_desired_interest() {
        return $this->desired_interest_payment;
    }

    public function get_is_principal_payment() {
        return $this->is_principal_payment;
    }
    
    public function set_payment_towards_principal($payment_towards_principal) {
        $this->payment_towards_principal = $payment_towards_principal;
    }

    public function set_payment_towards_interest($payment_towards_interest) {
        $this->payment_towards_interest = $payment_towards_interest;
    }

    public function set_ending_principal($principal) {
        $this->ending_principal = $principal;
    }

    public function get_payment_principal() {
        return $this->payment_towards_principal;
    }

    public function get_payment_interest() {
        return $this->payment_towards_interest;
    }

    public function get_ending_principal() {
        return $this->ending_principal;
    }


    /*****************************************
     * Begin Payment 'Class' Accessor Methods
     *****************************************/
    
    public function get_payment_class() {
        switch ($this->payment_class) {
            case 0:
                return "table-success";
            case 1:
                return "table-danger";
            case 2:
                return "table-light";
            case 3:
                return "table-warning";
        }
    }

    public function get_payment_class_as_int() {
        return $this->payment_class;
    }

    public function set_payment_class(string $payment_type) {
        switch ($payment_type) {
            case 'regular':
                $this->payment_class = 0;
                break;
            case 'missed':
                $this->payment_class = 1;
                break;
            case 'projected':
                $this->payment_class = 2;
                break;
            case 'insufficient':
                $this->payment_class = 3;
                break;
        }
    }
    
    /*****************************************
     * End Payment 'Class' Accessor Methods
     *****************************************/
    
    /**********************************************************************
     * END GET & SET METHODS
     **********************************************************************/
    
    /**********************************************************************
     * BEGIN CLASS METHODS
     **********************************************************************/
    
    /**
     * Computes the interest, principal payment amounts, and ending principal
     * 
     * @param float $principal
     * @param bool $is_principal_payment
     */
    public function compute_payment(float $principal, bool $is_principal_payment) {
        $this->is_principal_payment = $is_principal_payment;
        if ($is_principal_payment) {
            $this->payment_towards_interest = 0;
            $this->payment_towards_principal = $this->payment_amount;
            $this->ending_principal = $principal -= $this->payment_towards_principal;
        } else {
            $payment_info = $this->calculate_payment($principal, $this->payment_amount, $this->account->get_loan_rate());
            $this->payment_amount = $payment_info['amount'];
            $this->payment_towards_principal = $payment_info['principal'];
            $this->payment_towards_interest = $payment_info['interest'];
            $this->ending_principal = $principal - $this->payment_towards_principal;
        }
    }

    /**
     * Returns an array containing the interest and principal payment amounts
     * base on the balance given
     * 
     * @param float $principal
     * @param float $payment_amount
     * @return Array 'principal' and 'interest' indexes 
     */
    private function calculate_payment($principal, $payment_amount) {
        $rate = $this->account->get_loan_rate();
        $payment = [];
        $this->desired_interest_payment = round($principal * (($rate / 100) / 12), 2);

        if ($payment_amount < $this->desired_interest_payment) {
            $interest_payment = $payment_amount;
            if ($payment_amount > 0) {
                $this->set_payment_class('insufficient');
                $this->account->add_missed_payment();
            }
        } else {
            $interest_payment = $this->desired_interest_payment;
        }

        $principal_payment = round($payment_amount - $interest_payment, 2);
        $principal_payment = $principal_payment < 0 ? 0 : $principal_payment;



        // Check if we need to adjust payment amount, to avoid overpayment
        $payment_adjusted = $this->calculate_overpayment($principal, $principal_payment, $payment_amount);

        $payment['interest'] = $interest_payment;
        $payment['principal'] = $payment_adjusted['principal'];
        $payment['amount'] = $payment_adjusted['amount'];
        return $payment;
    }

    /**
     * Calculates the final payment adjustment, if needed, to avoid overpayment
     * 
     * @param float $principal
     * @param float $principal_payment
     * @param float $payment_amount
     * @return Array Adjusted principal payment and total payment amount, array keys 'amount' & 'principal'
     */
    private function calculate_overpayment(float $principal, float $principal_payment, float $payment_amount) : Array {
        // Is our payment amount higher than what we owe?
        if ($principal_payment > $principal) {
            // We need to reduce our payment amount to match
            //remove the excess principal payment, but keep interest payment the same
            $payment_amount = round($payment_amount - ($principal_payment - $principal), 2);
            $payment_principal = round($principal_payment - ($principal_payment - $principal), 2);
            $payment_adjusted['amount'] = $payment_amount;
            $payment_adjusted['principal'] = $payment_principal;
            $this->desired_payment = $payment_adjusted['amount'];
            return $payment_adjusted;
        } else {
            $payment_adjusted['amount'] = $payment_amount;
            $payment_adjusted['principal'] = $principal_payment;
            return $payment_adjusted;
        }
    }
    
    /**********************************************************************
     * END CLASS METHODS
     **********************************************************************/

}

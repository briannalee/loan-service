<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LoanService;

use DateTime;
use DateTimeZone;

/**
 * Provides various amortization table functions
 *
 * @author brianna
 */
class Amortization {

    private $account;
    private $interest_due;
    private $interest_paid;

    public function __construct(Account $account) {
        $this->account = $account;
    }

    public function currently_owe_interest() {
        return $this->interest_due >= $this->interest_paid ? true : false;
    }

    /**
     * Returns an array containing all the payments, real and projected
     * @return array Containing all payments
     */
    public function get_payment_array(): Array {
        $ending_principal = $this->account->get_loan_principal();
        $real_payments = $this->get_real_payments();
        if (count($real_payments) > 0) {
            $ending_principal = $real_payments[count($real_payments) - 1]->get_ending_principal();
        }

        // Generate projected payments
        $projected_payments = $this->generate_projected_payments($ending_principal, $this->account->get_loan_start_date());

        // Remove payments that are duplicates, if real payments exist
        if (count($real_payments) > 0) {
            $payments = $this->remove_duplicate_payments($real_payments, $projected_payments, 14);
        }

        // Sort payments
        $payments = $this->sort_by_date($payments);

        // Find missed payments
        $payments = $this->find_missed_payments($payments);
        
        // Reprocess our payments, based on missed payments
        $payments = $this->reprocess_payments($payments);

        // Add the remaining projected payments if existing ones are 
        // not enough to pay off the loan
        $ending_principal = $payments[count($payments) - 1]->get_ending_principal();

        if ($ending_principal > 0.01) {
            $ending_date = $payments[count($payments) - 1]->get_payment_date();
            $projected_payments = $this->generate_projected_payments($ending_principal, $ending_date);
            $payments = array_merge($payments, $projected_payments);
        }

        return $payments;
    }

    /**
     * Generates projected payments from the given date, until principal is 0
     * @param float $principal
     * @param DateTime $last_payment_date
     * @return array Containing projected payments
     */
    public function generate_projected_payments(float $principal, DateTime $last_payment_date): Array {

        $payment_amount = $this->account->get_loan_payment();
        $payments = [];
        $payment_index = 1;

        //Start in this month, on our payment day
        $payment_day = $this->account->get_loan_start_date()->format('j');
        $last_payment_date->modify("first day of this month");
        $last_payment_date->modify('+' . (min($payment_day, $this->account->get_loan_start_date()->format('t')) - 1) . ' days');



        //if ($last_payment_date > new DateTime("now", new DateTimeZone("UTC"))) {
        //$payment_index = 0;
        //}
        while ($principal > 0.01) {
            $payment_date = $this->add_months($last_payment_date, $payment_index);
            $payment = new Payment($payment_amount, $payment_date, $this->account);
            $payment->compute_payment($principal, false);
            $payment->set_payment_class('projected');
            $payments[] = $payment;
            $principal -= $payment->get_payment_principal();
            $payment_index++;
        }

        return $payments;
    }

    /**
     * Sorts the given array by payment date
     * 
     * @param array $payments
     * @return array Containing the sorted array
     */
    private function sort_by_date(Array $payments): Array {
        usort($payments, function($a, $b) {
            $ad = $a->get_payment_date();
            $bd = $b->get_payment_date();

            if ($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? -1 : 1;
        });

        return $payments;
    }

    /**
     * Replaces any duplicate projected payments with real payments
     * Adds in the remaining real payments to the end of the array, to be sorted
     * later
     * 
     * @param array $real_payments
     * @param array $projected_payments
     * @param float $payment_window
     * @return array Containing updated payments list containing all payments
     */
    private function remove_duplicate_payments(Array $real_payments, Array $projected_payments, float $payment_window): Array {
        foreach ($projected_payments as $projected_key => $projected_payment) {
            // Find the closest real payment to this payment
            $projected_payment_date = $projected_payment->get_payment_date();
            $closest_payment = $this->find_closest_payment($projected_payment_date, $real_payments);

            // Check to see if we found any payments at all
            if ($closest_payment === null) {
                continue;
            }

            // Is the payment within our payment window?
            $difference = $projected_payment_date->diff($closest_payment->get_payment_date())->format("%r%a");
            if ($difference > $payment_window) {
                continue;
            }

            // Replace the projected payment with our real payment
            $key = array_search($closest_payment, $real_payments);
            $projected_payments[$projected_key] = $real_payments[$key];

            // Remove this one from the array of available real payments
            unset($real_payments[$key]);
        }

        // Merge in any other payments, including principal only payments
        $projected_payments = array_merge($projected_payments, $real_payments);

        return $projected_payments;
    }

    /**
     * Returns the closest payment to the given date from the given array
     * 
     * @param DateTime $target_date
     * @param array $payments
     * @return \LoanService\Payment
     */
    private function find_closest_payment(DateTime $target_date, Array $payments): ?Payment {
        $closest_payment = null;
        $lowest_difference = null;

        foreach ($payments as $payment) {
            // Don't process principal payments
            if ($payment->get_is_principal_payment() > 0) {
                continue;
            }

            //Get the days between the payment date and target date
            $days_between = $target_date->diff($payment->get_payment_date())->format("%a");

            // Is this payment closer than our previous (or have we found none yet)?
            if ($lowest_difference === null || $days_between < $lowest_difference) {
                $closest_payment = $payment;
                $lowest_difference = $days_between;
            }
        }

        return $closest_payment;
    }

    /**
     * Replaces missed projected payments with an empty payment
     * 
     * @param array $payments
     * @return array Containing updated payment array
     */
    private function find_missed_payments(Array $payments): Array {
        $now = new DateTime("now", new DateTimeZone("UTC"));
        $principal = $this->account->get_loan_principal();
        for ($i = 0; $i < count($payments); $i++) {
            // Only projected payments can be missed
            if ($payments[$i]->get_payment_class_as_int() !== 2) {
                continue;
            }
            
            //Payment is projected, has it been missed?
            if ($payments[$i]->get_payment_date() < $now) {
                // Add a missed payment to the account
                $this->account->add_missed_payment();
                $payment_date = $payments[$i]->get_payment_date();
                $payments[$i] = new Payment(0, $payment_date, $this->account);
                $payments[$i]->set_payment_class('missed');
            }
        }
        return $payments;
    }
    
    /**
     * Reprocesses all payments
     * 
     * @param array $payments
     * @return array
     */
    private function reprocess_payments(Array $payments) : Array {
        $principal = $this->account->get_loan_principal();
        foreach($payments as $payment) {
            $payment->compute_payment($principal, $payment->get_is_principal_payment());
            $principal = $payment->get_ending_principal();
        }
        return $payments;
    }

    /**
     * Gets the real payments from the database and places them into a Payment
     * class
     * 
     * @param type $account_id
     * @return Array Containing Payment class for each real payment
     */
    private function get_real_payments() {
        $db_connection = Database::connect();
        $principal = $this->account->get_loan_principal();
        $account_id = $this->account->get_account_id();
        $regular_payments_made = 0;
        $query = "SELECT * FROM payments WHERE account_id='$account_id' ORDER BY date";
        $payment_result = mysqli_query($db_connection, $query);
        $payments = [];

        while ($payment_info = mysqli_fetch_assoc($payment_result)) {
            //There is payments, add to array
            $payment_date = new DateTime($payment_info['date'] . " UTC");
            $payment = new Payment($payment_info['payment_amount'], $payment_date, $this->account);

            // Is this a principal only payment?
            $is_principal_payment = (int) $payment_info['is_principal_payment'];

            $payment->compute_payment($principal, $is_principal_payment);

            $regular_payments_made += $is_principal_payment ? 0 : 1;

            $principal -= $payment->get_payment_principal();
            $payments[] = $payment;
        }

        return $payments;
    }

    /**
     * Adds months to a date, while preserving the day
     * 
     * @param DateTime $dt
     * @param int $months
     * @return DateTime Updated DateTime with $months added
     */
    private function add_months(DateTime $dt, int $months) : DateTime {
        $date = new DateTime($dt->format("Y-m-d"));
        $day = $date->format('j');
        $date->modify("first day of +$months month");
        $date->modify('+' . (min($day, $date->format('t')) - 1) . ' days');

        return $date;
    }

}

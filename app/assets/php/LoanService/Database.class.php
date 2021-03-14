<?php

namespace LoanService;
use mysqli;

/**
 * Provides database connectivity
 *
 * @author brianna
 */
class Database {

    const SERVERNAME = 'localhost';
    const USERNAME = 'database_user';
    const PASSWORD = 'database_password';
    const DATABASE = 'loan-service';

    /**
     * Returns a connection to the mysql database
     * @return mysqli
     */
    public static function connect() {
        // attempt to connect
        $conn = mysqli_connect(Database::SERVERNAME, Database::USERNAME, Database::PASSWORD, Database::DATABASE);
        if (!$conn) {
            // connection failed
            return "Failed to connect to MySQL: " . mysqli_connect_error();
        }else{
            // connection success
            return $conn;
        }
        
    }

}

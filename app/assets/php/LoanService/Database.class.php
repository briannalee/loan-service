<?php

namespace LoanService;

/**
 * Provides database connectivity
 *
 * @author brianna
 */
class Database {

    public const SERVERNAME = 'localhost';
    public const USERNAME = 'database_user';
    public const PASSWORD = 'Yba4kXS8h$Ip#19D89$5%Wd1AM4kl!2zmzo2';
    public const DATABASE = 'loan-service';

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

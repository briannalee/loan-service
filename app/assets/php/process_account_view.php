<?php
/**
 * Created by PhpStorm.
 * User: Brianna
 * Date: 1/18/2019
 * Time: 9:35 AM
 */

session_start();
$id = $_POST['id'];

spl_autoload_register(function ($class) {
    include $class . '.class.php';
});

$dbConnection = Database::connect();

$query="SELECT * FROM accounts,users WHERE accounts.id='$id' AND users.account_id='$id'";
$result=mysqli_query($dbConnection,$query);

if($row = mysqli_fetch_assoc($result)) {
    $query2="SELECT * FROM payments WHERE account_id='$id'";
    $result2=mysqli_query($dbConnection,$query2);
    $i = 0;
    while($row2 = mysqli_fetch_assoc($result2)) {
        //There is payments, add to array
        $row2['date'] = strtotime($row2['date']." UTC");
        $row['payments'][$i] = $row2;
        $i++;
    }
    if ($i < 1) {
        $row["payments"] = 0;
    }
    $row['loan_start_date'] = strtotime($row['loan_start_date']." UTC");
    echo json_encode($row);
}else{
    echo 0;
}

// Free result set
mysqli_free_result($result);

mysqli_close($dbConnection);

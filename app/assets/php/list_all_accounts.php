<?php

namespace LoanService;
/**
 * Created by PhpStorm.
 * User: Brianna
 * Date: 1/16/2019
 * Time: 3:23 AM
 */
$db_connection = Database::connect();

$sql_accounts="SELECT * FROM accounts ORDER BY id";
$result_accounts=mysqli_query($db_connection,$sql_accounts);



// Associative array
while ($account_info = mysqli_fetch_array($result_accounts, MYSQLI_ASSOC))
{
    $id = $account_info['id'];

    $sql_users="SELECT * FROM users WHERE account_id = '$id'";
    $result_users=mysqli_query($db_connection,$sql_users);
    $users = mysqli_fetch_array($result_users, MYSQLI_ASSOC);
    
    $last_name = $users['last_name'];
    $first_name = $users['first_name'];
    
    echo "<tr>";

    echo "<td data-label='Account #'>" . $id . "</td>";
    echo "<td data-label='Last Name' class='text-primary' style='cursor: pointer;' onclick='LoadAccountView(" . $id . ")'>" . $last_name . "<A/></td>";
    echo "<td data-label='First Name'>" . $first_name . "</td>";
    echo "<td data-label='Starting Balance'>" . $account_info["loan_amount"] . "</td>";
    echo "<td data-label='Current Balance'>" . $account_info["current_balance"] . "</td>";
    echo "<td data-label='Current Balance'>" . $account_info["loan_start_date"] . "</td>";

    echo "</tr>";
}


// Free result set
mysqli_free_result($result_accounts);
mysqli_free_result($result_users);

mysqli_close($db_connection);

?>
<div class="row">
                <div class="col-sm-6 ">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h3 class="card-title">Active Accounts</h3>
                        </div>
                        <div class="card-body p-l-25">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                    <tr><th>
                                            ID
                                        </th>
                                        <th>
                                            Last Name
                                        </th>
                                        <th>
                                            First Name
                                        </th>
                                        <th>
                                            Starting Balance
                                        </th>
                                        <th>
                                            Current Balance
                                        </th>
                                    </tr></thead>
                                    <tbody>
                                    


<?php
/**
 * Created by PhpStorm.
 * User: Brianna
 * Date: 1/16/2019
 * Time: 3:23 AM
 */
include("database_connect.php");
$dbConnection = InitiateDB();

$sql_accounts="SELECT * FROM accounts ORDER BY id";
$result_accounts=mysqli_query($dbConnection,$sql_accounts);



// Associative array
while ($row = mysqli_fetch_array($result_accounts, MYSQLI_ASSOC))
{
    $id = $row['id'];

    $sql_users="SELECT * FROM users WHERE account_id = '$id'";
    $result_users=mysqli_query($dbConnection,$sql_users);
    $users = mysqli_fetch_array($result_users, MYSQLI_ASSOC);
    
    $last_name = $users['last_name'];
    $first_name = $users['first_name'];
    
    echo "<tr>";

    echo "<td data-label='Account #'>" . $id . "</td>";
    echo "<td data-label='Last Name' class='text-primary' style='cursor: pointer;' onclick='LoadAccountView(" . $id . ")'>" . $last_name . "<A/></td>";
    echo "<td data-label='First Name'>" . $first_name . "</td>";
    echo "<td data-label='Starting Balance'>" . $row["loan_amount"] . "</td>";
    echo "<td data-label='Current Balance'>" . $row["current_balance"] . "</td>";

    echo "</tr>";
}


// Free result set
mysqli_free_result($result_accounts);
mysqli_free_result($result_users);

mysqli_close($dbConnection);

?>
</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 ">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h3 class="card-title">Account View</h3>
                        </div>
                        <div class="card-body p-l-25">
                            <div class="row">
                                <div class="progressOverlay" id="progressIndicator" style="visibility: hidden;">
                                    <img src="../assets/images/progress.gif" width="64" height="64">
                                </div>
                            <div class="col-sm-6 ">
                                <div class="row">
                                    <div class="col-sm-6 text-primary">Last Name: </div>
                                    <div id="lastName" class="col-sm-6 ">---</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 text-primary">First Name: </div>
                                    <div id="firstName" class="col-sm-6 ">---</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 text-primary">Email: </div>
                                    <div id="email" class="col-sm-6 ">--- </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 text-primary">Phone: </div>
                                    <div id="phone" class="col-sm-6 ">--- </div>
                                </div>
                            </div>
                            <div class="col-sm-6 ">
                                <div class="row">
                                    <div class="col-sm-6 text-primary">Current Balance: </div>
                                    <div id="currentBalance" class="col-sm-6 ">--- </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 text-primary">Starting Balance: </div>
                                    <div id="startingBalance" class="col-sm-6 ">--- </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 text-primary">Last Payment: </div>
                                    <div id="lastPayment" class="col-sm-6 ">--- </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 text-primary">Proposed Accrual: </div>
                                    <div id="accrual" class="col-sm-6 ">--- </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 text-primary">Monthly Payment: </div>
                                    <div id="monthlyPayment" class="col-sm-6 ">--- </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 text-primary">Start Date: </div>
                                    <div id="startDate" class="col-sm-6 ">--- </div>
                                </div>
                            </div>
                            <div class="col-sm-12 ">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class=" text-primary">
                                        <tr><th>
                                                Payment #
                                            </th>
                                            <th>
                                                Suggested Payment
                                            </th>
                                            <th>
                                                Actual Payment
                                            </th>
                                            <th>
                                                Interest
                                            </th>
                                            <th>
                                                Principal
                                            </th>
                                            <th>
                                                Balance
                                            </th>
                                            <th>
                                                Unpaid Accrued Interest
                                            </th>
                                            <th>
                                                Payment Date
                                            </th>
                                        </tr></thead>
                                        <tbody id="tableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<script src="../app/assets/js/load_account_view.js"></script>
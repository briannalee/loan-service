

<div class="row">
    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Account View</h4>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="account-tab" data-toggle="tab" href="#account_detail" role="tab" aria-controls="account_detail" aria-selected="false">Account Detail</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="payment-tab" data-toggle="tab" href="#add_payment" role="tab" aria-controls="add_payment" aria-selected="false">Add Payment</a>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="data-tables datatable-dark">
                            <table id="dataTable3" class="text-center" style="width:100%">
                                <thead class="text-capitalize">
                                    <tr>
                                        <th>ID</th>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Starting Balance</th>
                                        <th>Current Balance</th>
                                        <th>Payment Day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php include('assets/php/list_all_accounts.php') ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="account_detail" role="tabpanel" aria-labelledby="profile-tab">

                        <div class="progressOverlay" id="progressIndicator" style="visibility: hidden;">
                            <img src="../assets/images/progress.gif" width="64" height="64">
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="single-table">
                                    <div class="table-responsive">
                                        <table class="table text-center">
                                            <tbody>
                                                <tr>
                                                    <td>Last Name</td>
                                                    <td id="lastName">---</td>
                                                </tr>
                                                <tr>
                                                    <td>First Name</td>
                                                    <td id="firstName">---</td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td id="email">---</td>

                                                </tr>
                                                <tr>
                                                    <td>Phone</td>
                                                    <td id="phone">---</td>
                                                </tr>
                                                <tr>
                                                    <td>Proposed Accrual</td>
                                                    <td id="accrual">---</td>
                                                </tr>
                                                <tr>
                                                    <td>Missed Payments</td>
                                                    <td id="missed_payments">---</td>
                                                </tr>
                                                <tr>
                                                    <td>Current Late Charges</td>
                                                    <td id="late_charges">---</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="single-table">
                                    <div class="table-responsive">
                                        <table class="table text-center">
                                            <tbody>
                                                <tr>
                                                    <td>Start Date</td>
                                                    <td id="startDate">---</td>
                                                </tr>
                                                <tr>
                                                    <td>Starting Balance</td>
                                                    <td id="startingBalance">---</td>
                                                </tr>
                                                <tr>
                                                    <td>Current Balance</td>
                                                    <td id="currentBalance">---</td>
                                                </tr>
                                                <tr>
                                                    <td>Rate</td>
                                                    <td id="rate">---</td>
                                                </tr>
                                                <tr>
                                                    <td>Monthly Payment</td>
                                                    <td id="monthlyPayment">---</td>
                                                </tr>
                                                <tr>
                                                    <td>Last Payment</td>
                                                    <td id="lastPayment">---</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div> 

                        <div class="col-sm-12 ">
                            <h4 class="header-title">Payment Schedule</h4>
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
                                                Interest Paid
                                            </th>
                                            <th>
                                                Principal
                                            </th>
                                            <th>
                                                Balance
                                            </th>
                                            <th>
                                                Payment Due
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
                    <div class="tab-pane fade" id="add_payment" role="tabpanel" aria-labelledby="payment-tab">
                        <h4 class="header-title">Add Payment</h4>
                        <div class="alert alert-success alert-dismissible fade show" style="display: none;" id="payment_success" role="alert">
                            <strong>Success!</strong> Payment Added
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span class="fa fa-times"></span>
                            </button>
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show" style="display: none;" id="payment_failure" role="alert">
                            <strong>Failure!</strong> Payment Failed To Add! Please contact your administrator.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span class="fa fa-times"></span>
                            </button>
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show" style="display: none;" id="form_error" role="alert">
                            <strong>Error!</strong> Please review the form for any errors below.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span class="fa fa-times"></span>
                            </button>
                        </div>
                        <div class="row">

                            <div class="col-2">Account ID:</div><div class="col-2" id="payment_account_id">---</div>
                            <div class="col-2">Account Last Name:</div><div class="col-2" id="payment_account_last_name">---</div>
                            <div class="col-2">Current Balance:</div><div class="col-2" id="payment_account_current_balance">---</div>
                        </div>
                        <div class="row">
                            <div class="col-2">Monthly Payment:</div><div class="col-2" id="payment_account_monthly_payment">---</div>
                            <div class="col-2">Late Charges Due:</div><div class="col-2" id="payment_account_late_charges">---</div>
                            <div class="col-2">Suggested Minimum Payment:</div><div class="col-2" id="payment_minimum_payment">---</div>
                        </div>
                        <br>
                        <div id="row">
                            <form id="add_payment_form">
                                <label for="payment_amount">Payment Amount</label>
                                <div class="input-group mb-3">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" name="payment_amount" required class="form-control col-4" aria-label="Amount" id="payment_amount" aria-describedby="paymentHelp" placeholder="0.00" step="any" min="1">

                                </div>
                                <label for="payment_amount" generated="true" class="error text-danger"></label>
                                <div class="form-group">
                                    <label for="payment_date" class="col-form-label">Payment Date</label>
                                    <input class="form-control col-4" type="date" id="payment_date" name="payment_date" required>
                                    <label for="payment_date" generated="true" class="error text-danger"></label>

                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="principal_payment">
                                    <label class="form-check-label" for="principal_payment">Is Principal Payment</label>
                                </div>
                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4" id="add_payment_submit">Add Payment</button>
                                <div class="progressOverlay" id="progressIndicator" style="visibility: hidden;">
                                    <img src="../assets/images/progress.gif" width="64" height="64">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>

<script src="../app/assets/js/load_account_view.js"></script>
<script src="../app/assets/js/add_payment.js"></script>
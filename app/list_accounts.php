

<div class="row">
    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Data Table Dark</h4>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="account-tab" data-toggle="tab" href="#account_detail" role="tab" aria-controls="account_detail" aria-selected="false">Account Detail</a>
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
                                                    <td>Accrued Interest</td>
                                                    <td id="accruedInterest">---</td>
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
</div>

<script src="../app/assets/js/load_account_view.js"></script>
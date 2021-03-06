

<div class="row">
    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Add Account</h4>
                        <div class="alert alert-success alert-dismissible fade show" style="display: none;" id="payment_success" role="alert">
                            <strong>Success!</strong> Account Added
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span class="fa fa-times"></span>
                            </button>
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show" style="display: none;" id="payment_failure" role="alert">
                            <strong>Failure!</strong> Account Failed To Add! Please contact your administrator.
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
                        <div id="row">
                            <form id="add_account_form">
                                <div class="form-group">
                                    <label for="first_name" class="col-form-label">First Name</label>
                                    <input class="form-control" type="text" id="first_name" name="first_name" required>
                                    <label for="first_name" generated="true" class="error text-danger"></label>
                                </div>
                                <div class="form-group">
                                    <label for="last_name" class="col-form-label">Last Name</label>
                                    <input class="form-control" type="text" id="last_name" name="last_name" required>
                                    <label for="last_name" generated="true" class="error text-danger"></label>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-form-label">Email</label>
                                    <input class="form-control" type="email" id="email" name="email" required>
                                    <label for="email" generated="true" class="error text-danger"></label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone" class="col-form-label">Phone</label>
                                    <input class="form-control" type="phone" id="phone" name="phone" required>
                                    <label for="phone" generated="true" class="error text-danger"></label>
                                </div>
                                
                                <label for="loan_amount">Loan Amount</label>
                                <div class="input-group mb-3">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" name="loan_amount" required class="form-control" aria-label="Amount" id="loan_amount" aria-describedby="paymentHelp" placeholder="20,000.00" step="any" min="1">
									
                                </div>
                                <label for="loan_amount" generated="true" class="error text-danger"></label><br>
                                
                                
                                
                                <label for="loan_rate">Loan Rate</label>
                                <div class="input-group mb-3">

                                    
                                    <input type="number" name="loan_rate" required class="form-control" aria-label="Amount" id="loan_rate" aria-describedby="paymentHelp" placeholder="5.50" step="0.01" min="1">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>

                                </div>
                                <label for="loan_rate" generated="true" class="error text-danger"></label><br>
                                
                                <label for="loan_length">Loan Length</label>
                                <div class="input-group mb-3">

                                    
                                    <input type="number" name="loan_length" required class="form-control" aria-label="Amount" id="loan_length" aria-describedby="paymentHelp" placeholder="36" step="1" min="1">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Months</span>
                                    </div>

                                </div>
                                <label for="loan_length" generated="true" class="error text-danger"></label>
                                
                                <div class="form-group">
                                    <label for="loan_date" class="col-form-label">Loan Date</label>
                                    <input class="form-control" type="date" id="loan_date" name="loan_date" required>
                                    <label for="loan_date" generated="true" class="error text-danger"></label>
                                </div>
                                
                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4" id="add_account_submit" onclick="AddAccount(); return false;">Add Account</button>
                                <div class="progressOverlay" id="progressIndicator" style="visibility: hidden;">
                                    <img src="../assets/images/progress.gif" width="64" height="64">
                                </div>
                            </form>
                        </div>
            </div>
        </div>
    </div>
</div>

<script src="../app/assets/js/add_account.js"></script>

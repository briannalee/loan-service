<!doctype html>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Loan Servicing App</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
        <link rel="stylesheet" href="../app/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../app/assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="../app/assets/css/themify-icons.css">
        <link rel="stylesheet" href="../app/assets/css/metisMenu.css">
        <link rel="stylesheet" href="../app/assets/css/owl.carousel.min.css">
        <link rel="stylesheet" href="../app/assets/css/slicknav.min.css">
        <!-- amchart css -->
        <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
        <!-- datatable css -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
        <!-- others css -->
        <link rel="stylesheet" href="../app/assets/css/typography.css">
        <link rel="stylesheet" href="../app/assets/css/default-css.css">
        <link rel="stylesheet" href="../app/assets/css/styles.css">
        <link rel="stylesheet" href="../app/assets/css/responsive.css">
        <!-- modernizr css -->
        <script src="../app/assets/js/vendor/modernizr-2.8.3.min.js"></script>
    </head>

    <body>
        <!--[if lt IE 8]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
            <![endif]-->
        <!-- preloader area start -->
        <div id="preloader">
            <div class="loader"></div>
        </div>
        <!-- preloader area end -->
        <!-- page container area start -->
        <div class="page-container">
            <!-- sidebar menu area start -->
            <div class="sidebar-menu">
                <div class="sidebar-header">
                    <div class="logo">
                        <h5><a href="index.html">Loan Service</a></h5>
                    </div>
                </div>
                <div class="main-menu">
                    <div class="menu-inner">
                        <nav>
                            <ul class="metismenu" id="menu">
                                <li id="dash"><a href="dash"><i class="ti-map-alt"></i> <span>Dashboard</span></a></li>
                                <li>
                                    <a href="javascript:void(0)" aria-expanded="true" id="account"><i class="ti-dashboard"></i><span>Accounts</span></a>
                                    <ul class="collapse">
                                        <li id="list_accounts"><a href="list_accounts">View Accounts</a></li>
                                        <li id="add_account"><a href="add_account">Add Account</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" aria-expanded="true" id="admin"><i class="ti-layout-sidebar-left"></i><span>Admin
                                        </span></a>
                                    <ul class="collapse">
                                        <li  id="admin_users"><a href="admin_users">Manager Admin Users</a></li>
                                        <li id="admin_settings"><a href="admin_settings" >My Account</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- sidebar menu area end -->
            <!-- main content area start -->
            <div class="main-content">
                <!-- page title area start -->
                <div class="page-title-area">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <div class="breadcrumbs-area clearfix">
                                <div class="nav-btn pull-left nav-btn-menu">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                                <h4 class="page-title pull-left"><?php echo $page_name; ?></h4>
                            </div>
                        </div>
                        <div class="col-sm-6 clearfix">
                            <div class="user-profile pull-right">
                                <h4 class="user-name dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['last_name']; ?><i class="fa fa-angle-down"></i></h4>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="admin_settings">Settings</a>
                                    <a class="dropdown-item" href="#" id="logout" onclick="Logout();">Log Out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- page title area end -->
                <div class="main-content-inner">

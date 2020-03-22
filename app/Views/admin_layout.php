<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Income and Expense tracker for business and personal use.">
    <meta name="author" content="Simcy Creative">
    <link rel="icon" type="image/png" sizes="16x16" href="uploads/app/yv91yZHRY2MB84Y3vAnyGz89LYOBLDYm.png">
    <title><?php echo @$title ? $title : 'Dashboard'; ?></title>

    <link href="<?php echo base_url('assets/libs/slider/css/bootstrap-slider.min.css'); ?>" rel="stylesheet"/>
    <link href="<?php echo base_url('assets/libs/daterangepicker/daterangepicker.css'); ?>" rel="stylesheet"/>
    <!-- Material design icons -->
    <link href="<?php echo base_url('assets/fonts/mdi/css/materialdesignicons.min.css'); ?>" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url('assets/libs/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/simcify.min.css'); ?>" rel="stylesheet">
    <!-- Signer CSS -->
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
</head>


<body>


<header>
    <!-- Humbager -->
    <div class="humbager">
        <i class="mdi mdi-menu"></i>
    </div>
    <!-- logo -->
    <div class="branding">
        <a href="<?php echo site_url('admin'); ?>">
            <img src="<?php echo base_url('uploads/app/XSiE8IvjO9M0XksmVYiPuqgU3gekwgGt.png'); ?>" class="img-responsive">
        </a>
    </div>

    <!-- Navigation -->
    <nav class="navigation">
        <ul class="nav navbar-nav">
            <li><a href="<?php echo site_url('admin'); ?>">Overview</a></li>
            <li><a href="<?php echo site_url('admin/transactions'); ?>">Transactions</a></li>
            <li><a href="<?php echo site_url('admin/customers'); ?>">My Customers</a></li>
            <li><a href="<?php echo site_url('admin/paybill'); ?>">Settings</a></li>
            <li class="close-menu"><a href="#"><i class="mdi mdi-close-circle-outline"></i> Close</a></li>
        </ul>
    </nav>

    <!-- Right content -->
    <div class="header-right">
        <div class="dropdown hidden-sm hidden-xs">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Shortcodes <span><i class="mdi mdi-arrow-down-drop-circle-outline"></i></span></button>
            <ul class="dropdown-menu">
                <li role="presentation"><a role="menuitem" data-toggle="modal" data-target="#addShortcode"> <i class="mdi mdi-chevron-right"></i> New Shortcode</a></li>
                <?php
                $businesses = new \App\Models\BusinessModel();
                $businesses = $businesses->findAll();
                if($businesses && count($businesses) > 0) {
                    foreach ($businesses as $business) {
                        ?>
                        <li role="presentation"><a role="menuitem" class="send-to-server-click" data="id:<?php echo $business->id; ?>|status:1" url="<?php echo site_url('admin/paybill/switch/'.$business->id); ?>" warning-title="Switching Shortcodes" warning-message="You are about to switch to <?php echo $business->name; ?>" warning-button="Continue" loader="true" > <i class="mdi mdi-switch"></i> <?php echo $business->name.' ('.$business->shortcode.')'; ?></a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <div class="dropdown">
            <span class="dropdown-toggle" data-toggle="dropdown">
                <span class="avatar">
                     <img src="<?php echo base_url('assets/images/avatar.png'); ?>" class="img-circle">
                </span>
                <span class="profile-name">
                    <span class="hidden-xs"><?php echo ucwords($user->first_name); ?></span>
                    <i class="mdi mdi-menu-down-outline"></i>
                </span>
            </span>
            <ul class="dropdown-menu profile-menu" role="menu" aria-labelledby="menu1">
                <li role="presentation"><a role="menuitem" href="<?php echo site_url('admin/paybill'); ?>"> <i class="mdi mdi-settings"></i> Settings</a></li>
                <li role="presentation"><a role="menuitem" href="<?php echo site_url('auth/logout'); ?>"> <i class="mdi mdi-logout"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</header>
<!-- Main content -->
<div class="container">
    <div class="page-heading">
        <?php
        $business = active_business();
        if(isset($business->api_setup) && $business->api_setup != 1) {
            ?>
            <div>
                <div class="alert alert-danger">
                    No Transaction will be posted here because you have not set up the API. Click the Button on the right to setup.
                </div>
                <button class="btn btn-primary pull-right ml-5 send-to-server-click" data="id:<?php echo $business->id; ?>|status:1" url="<?php echo site_url('admin/api/setup/'.$business->id); ?>" warning-title="API Setup" warning-message="You are about to register URLs to M-Pesa to receive transaction details" warning-button="Continue" loader="true" type="button"><span><i class="glyphicon glyphicon-cog"></i></span> Set Up API</button>
            </div>
            <?php
        }
        ?>
        <div class="heading-content">
            <div class="user-image">
                <img src="<?php echo base_url('assets/images/avatar.png'); ?>" class="img-circle img-responsive">
            </div>
            <div class="heading-title">
                <h2><?php echo strtoupper($business->name); ?></h2>
                <p>This is your dashboard. Overview of almost everything.</p>
            </div>
        </div>
    </div>
    <?php echo @$_content; ?>
    <!-- footer -->
    <footer>
        <div class="footer-logo">
            <img src="<?php echo base_url('uploads/app/XSiE8IvjO9M0XksmVYiPuqgU3gekwgGt.png'); ?>" class="img-responsive">
        </div>
        <p class="text-right pull-right">&copy; <?php echo date('Y') ?> Bennito254 <span>•</span> All Rights Reserved.</p>
    </footer>


    <!-- add income -->
    <div class="modal fade" id="addIncome" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Record Income</h4>
                </div>
                <div class="modal-body">
                    <p>Save a new income record.</p>
                    <form class="simcy-form" action="http://hellaplus.simcycreative.com/income/add/" data-parsley-validate="" method="POST" loader="true">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title" placeholder="i.e Salary" required="">
                                    <input type="hidden" name="csrf-token" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Amount</label>
                                    <span class="input-prefix">$</span>
                                    <input type="number" class="form-control prefix" name="amount" min="1" placeholder="Amount" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Account</label>
                                    <select class="form-control select2" name="account">
                                        <option value="00">Other</option>
                                        <option value="103">FOOD</option>
                                        <option value="101">Stripe</option>
                                        <option value="81">IT Software</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 ">
                                    <label>Group</label>
                                    <select class="form-control select2" name="income_group">
                                        <option value="Salary">Salary</option>
                                        <option value="Investments">Investments</option>
                                        <option value="Donations">Donations</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Date</label>
                                    <input type="text" class="form-control datepicker" name="income_date" placeholder="Date" required="">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Income</button>
                </div>
                </form>
            </div>

        </div>
    </div>


    <!--Record Expense-->
    <div class="modal fade" id="addShortcode" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Shortcode</h4>
                </div>
                <form class="simcy-form" action="<?php echo site_url('admin/paybill/create'); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p class="text-center">Create a new business shortcode.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Shortcode Name" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Shortcode (Paybill or Store Number)</label>
                                    <input type="number" class="form-control" min="1" name="shortcode" placeholder="Shortcode Number" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Shortcode Type</label>
                                    <select class="form-control select2" name="type" require="">
                                        <option value="C2B">Client to Business</option>
                                        <option value="B2C">Business to Client</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>API Consumer Key</label>
                                    <input type="text" class="form-control" name="consumer_key" placeholder="Consumer Key for the production App" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>API Consumer Secret</label>
                                    <input type="text" class="form-control" name="consumer_secret" placeholder="Consumer Secret for the production App" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Initiator Username</label>
                                    <input type="text" class="form-control" name="initiator_username" placeholder="Initiator Username">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Initiator Password</label>
                                    <input type="text" class="form-control" name="initiator_password" placeholder="Initiator Password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Shortcode</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

<!-- scripts -->
<script src="<?php echo base_url('assets/js/jquery-3.2.1.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/moment.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/libs/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/libs/daterangepicker/daterangepicker.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.slimscroll.min.js'); ?>"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>
<script src="<?php echo base_url('assets/js/simcify.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/echarts.min.js'); ?>"></script>
<!-- custom scripts -->
<script src="<?php echo base_url('assets/js/overview.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/app.js'); ?>"></script>
</body>
</html>

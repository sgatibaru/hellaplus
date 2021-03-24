<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Income tracker for business using M-Pesa via the API">
    <meta name="author" content="Bennito254.com">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_option('site_logo', base_url('uploads/app/XSiE8IvjO9M0XksmVYiPuqgU3gekwgGt.png')); ?>">
    <title><?php use App\Models\BusinessModel;

        echo @$title ? $title : 'Dashboard'; ?></title>

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
<?php
$active_business = active_business();
?>
<header>
    <!-- Humbager -->
    <div class="humbager">
        <i class="mdi mdi-menu"></i>
    </div>
    <!-- logo -->
    <div class="branding">
        <a href="<?php echo site_url('admin'); ?>">
            <img src="<?php echo get_option('site_logo', base_url('uploads/app/XSiE8IvjO9M0XksmVYiPuqgU3gekwgGt.png')); ?>"
                 class="img-responsive">
        </a>
    </div>

    <!-- Navigation -->
    <nav class="navigation">
        <ul class="nav navbar-nav">
            <?php
            if((new \App\Libraries\IonAuth())->isAdmin()) {
                ?>
                <li class="">
                    <a href="<?php echo site_url(route_to('dashboard.index')); ?>">Dashboard</a>
                </li>
                <?php
            }
            ?>
            <li><a href="<?php echo site_url('admin'); ?>">Overview</a></li>
            <li>
                <a href="<?php echo site_url('admin/transactions'); ?>"><?php echo (isset($active_business) && @$active_business->type == 'B2C') ? 'Disbursements' : 'Transactions'; ?></a>
            </li>
            <li><a href="<?php echo site_url('admin/customers'); ?>">My Customers</a></li>
            <li><a href="<?php echo site_url('admin/paybill/settings'); ?>">Settings</a></li>
            <li class="close-menu"><a href="#"><i class="mdi mdi-close-circle-outline"></i> Close</a></li>
        </ul>
    </nav>

    <!-- Right content -->
    <div class="header-right">
        <div class="dropdown hidden-sm hidden-xs">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Shortcodes <span><i
                            class="mdi mdi-arrow-down-drop-circle-outline"></i></span></button>
            <ul class="dropdown-menu">
                <li role="presentation"><a role="menuitem" data-toggle="modal" data-target="#addShortcode"> <i
                                class="mdi mdi-plus"></i> New Shortcode</a></li>
                <li class="divider"></li>
                <?php
                $businesses = new BusinessModel();
                $businesses = $businesses->where('user', $user->id)->findAll();
                if ($businesses && count($businesses) > 0) {
                    foreach ($businesses as $business) {
                        ?>
                        <li role="presentation"><a role="menuitem" class="send-to-server-click"
                                                   data="id:<?php echo $business->id; ?>|status:1"
                                                   url="<?php echo site_url('admin/paybill/switch/' . $business->id); ?>"
                                                   warning-title="Switching Shortcodes"
                                                   warning-message="You are about to switch to <?php echo $business->name; ?>"
                                                   warning-button="Continue" loader="true"> <i
                                        class="mdi mdi-switch"></i> <?php echo $business->name . ' (' . $business->shortcode . ')'; ?>
                            </a></li>
                        <?php
                    }
                }
                ?>
                <li class="divider"></li>
                <li>
                    <a href="<?php echo site_url(route_to('admin.settings.shortcodes')); ?>"><i class="mdi mdi-wrench"></i> Manage Shortcodes</a>
                </li>
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
                <li role="presentation"><a role="menuitem" href="<?php echo site_url('admin/paybill'); ?>"> <i
                                class="mdi mdi-settings"></i> App Settings</a></li>
                <li role="presentation"><a role="menuitem" href="<?php echo site_url('auth/logout'); ?>"> <i
                                class="mdi mdi-logout"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</header>
<!-- Main content -->
<div class="container">
    <?php
    if(!isset($admin_dashboard) || $admin_dashboard !== true) {
        ?>
        <div class="page-heading">
            <?php
            if (@$active_business->type != 'B2C') {
                if(isset($active_business->api_setup) && $active_business->api_setup != 1) {
                    ?>
                    <div>
                        <div class="alert alert-danger">
                            No Transaction will be posted here because you have not set up the API. Click the Button on the
                            right to setup.
                        </div>
                        <button class="btn btn-primary pull-right ml-5 send-to-server-click"
                                data="id:<?php echo $business->id; ?>|status:1"
                                url="<?php echo site_url('admin/api/setup/' . $business->id); ?>" warning-title="API Setup"
                                warning-message="You are about to register URLs to M-Pesa to receive transaction details"
                                warning-button="Continue" loader="true" type="button"><span><i
                                        class="glyphicon glyphicon-cog"></i></span> Set Up API
                        </button>
                    </div>
                    <?php
                }
            } else {
                ?>
                <button class="btn btn-primary pull-right ml-5" type="button" data-toggle="modal" data-target="#NewB2C">
                    <span><i class="glyphicon glyphicon-send"></i></span> Send Money
                </button>
                <div class="modal fade" id="NewB2C" role="dialog">
                    <div class="modal-dialog modal-md">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <form class="simcy-form" method="post" action="<?php echo site_url(route_to('admin.transactions.send_money')); ?>" data-parsley-validate="" loader="true">
                                <div class="modal-header">
                                    <h4 class="modal-title">Send Money</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Transaction Type</label>
                                        <select class="form-control select2" name="command" required>
                                            <option>-- Please select --</option>
                                            <option value="SalaryPayment">Salary Payment</option>
                                            <option value="PromotionPayment">Promotion Payment</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" name="phone" min="0" placeholder="Phone Number"
                                               required/>
                                        <small><span class="text-danger"><i class="glyphicon glyphicon-warning-sign"></i> confirm this is the actual phone number. No in-app validation for this!</span></small>
                                    </div>
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="number" class="form-control" name="amount" min="50" value="50" max="70000"
                                               placeholder="Amount to send" required/>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Send Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="heading-content">
                <div class="user-image">
                    <img src="<?php echo base_url('assets/images/avatar.png'); ?>" class="img-circle img-responsive">
                </div>
                <div class="heading-title">
                    <h2><?php echo ($active_business) ? strtoupper($active_business->name) : 'No Shortcodes set up!'; ?></h2>
                    <p>This is your dashboard. Overview of almost everything.</p>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <?php echo @$_content; ?>
    <!-- footer -->
    <footer>
        <div class="footer-logo">
            <img src="<?php echo get_option('site_logo', base_url('uploads/app/XSiE8IvjO9M0XksmVYiPuqgU3gekwgGt.png')); ?>"
                 class="img-responsive">
        </div>
        <p class="text-right pull-right">&copy; <?php echo date('Y') ?> <a target="_blank" href="https://bennito254.com"><?php echo get_option('site_name', 'Bennito254'); ?></a> <span>•</span> Version <?php echo get_option('_app_version', '1.0.1'); ?>
        </p>
    </footer>

    <!--Record Expense-->
    <div class="modal fade" id="addShortcode" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Shortcode</h4>
                </div>
                <form class="simcy-form" action="<?php echo site_url('admin/paybill/create'); ?>"
                      data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user" value="<?php echo (new \App\Libraries\IonAuth())->getUserId() ?>">
                    <div class="modal-body">
                        <p class="text-center">Create a new business shortcode.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Shortcode Name"
                                           required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>API Environment</label>
                                    <select class="form-control select2" name="env" require="">
                                        <option value="live">Live/Production</option>
                                        <option value="sandbox">Sandbox</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Shortcode (Paybill or Store Number)</label>
                                    <input type="number" class="form-control" min="1" name="shortcode"
                                           placeholder="Shortcode Number" required="">
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
                                    <input type="text" class="form-control" name="consumer_key"
                                           placeholder="Consumer Key for the production App" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>API Consumer Secret</label>
                                    <input type="text" class="form-control" name="consumer_secret"
                                           placeholder="Consumer Secret for the production App" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Initiator Username</label>
                                    <input type="text" class="form-control" name="initiator_username"
                                           placeholder="Initiator Username">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Initiator Password</label>
                                    <input type="text" class="form-control" name="initiator_password"
                                           placeholder="Initiator Password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Encrypted Security Credential</label>
                                    <textarea type="text" class="form-control" name="security_credential"></textarea>
                                    <small>Please encrypt your Initiator password <a href="https://developer.safaricom.co.ke/test_credentials">on the developers portal, here.</a></small>
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
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
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

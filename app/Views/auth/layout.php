<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Income and Expense tracker for business and personal use.">
    <meta name="author" content="Simcy Creative">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('uploads/app/yv91yZHRY2MB84Y3vAnyGz89LYOBLDYm.png'); ?>">
    <title>Authentication</title>
    <!-- Material design icons -->
    <link href="<?php echo base_url('assets/fonts/mdi/css/materialdesignicons.min.css'); ?>" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url('assets/libs/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/simcify.min.css'); ?>" rel="stylesheet">
    <!-- Signer CSS -->
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
</head>
<body>


<div class="auth-page">
    <div class="auth-card card">
        <div class="auth-logo">
            <img src="<?php echo base_url('uploads/app/XSiE8IvjO9M0XksmVYiPuqgU3gekwgGt.png'); ?>" class="img-responsive">
        </div>
        <?php
        if($message) {
            ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php
        }
        ?>
        <div class="login">
            <div class="auth-heading mt-15">
                <h2 class="text-center">Welcome Back</h2>
                <p class="text-center">Please login to your account</p>
            </div>
            <div class="auth-form">
                <form class="simcy-form" action="<?php echo site_url('auth/login'); ?>" data-parsley-validate="" method="POST" loader="true">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Email Address</label>
                                <input type="email" class="form-control" name="identity" placeholder="Email Address" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="" class="auth-switch pull-right mt-10 text-muted text-thin" show=".forgot">Forgot Password?</a>

                                <button type="submit" class="btn btn-primary">Login Now</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="forgot">
            <div class="auth-heading mt-15">
                <h2 class="text-center">Forgot password</h2>
                <p class="text-center">Don't worry if you forgot your password, enter your email and you can reset it.</p>
            </div>
            <div class="auth-form">
                <form class="simcy-form" action="<?php echo site_url('auth/forgot-password'); ?>" data-parsley-validate="" method="POST" loader="true">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Email Address</label>
                                <input type="email" class="form-control" name="identity" placeholder="Email Address" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-block">Reset Password</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="">
                <p class="text-muted text-thin mt-40">Remembered your password? <a href="" class="auth-switch text-primary" show=".login">Login Now</a></p>
            </div>
        </div>
    </div>
    <p class="copyright text-thin text-muted"> &copy; <?php echo date('Y') ?> Bennito254 <span>•</span> All Rights Reserved.</p>
</div>

<!-- scripts -->
<script src="<?php echo base_url('assets/js/jquery-3.2.1.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/libs/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js//jquery.slimscroll.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/simcify.min.js'); ?>"></script>
<!-- custom scripts -->
<script src="<?php echo base_url('assets/js/app.js'); ?>"></script>
</body>
</html>
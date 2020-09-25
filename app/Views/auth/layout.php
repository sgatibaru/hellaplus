<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Income tracker for businesses using M-Pesa API">
    <meta name="author" content="Bennito254.com">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_option('site_logo', base_url('uploads/app/XSiE8IvjO9M0XksmVYiPuqgU3gekwgGt.png')); ?>">
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
            <img src="<?php echo get_option('site_logo', base_url('uploads/app/XSiE8IvjO9M0XksmVYiPuqgU3gekwgGt.png')); ?>" class="img-responsive">
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
                                <?php
                                if(get_option('allow_registration', 1)) {
                                    ?>
                                    <a href="" class="auth-switch pull-right mt-10 text-muted text-thin" show=".register">Create New Account</a>
                                    <?php
                                }
                                ?>
                                <a href="" class="auth-switch pull-right mt-10 text-muted text-thin" show=".forgot">Forgot Password?</a>
                                <button type="submit" class="btn btn-primary">Login Now</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
        if(get_option('allow_registration', 1)) {
            ?>
            <div class="register">
                <div class="auth-heading mt-15">
                    <h2 class="text-center">Welcome</h2>
                    <p class="text-center">Registration Form</p>
                </div>
                <div class="auth-form">
                    <form class="simcy-form" action="<?php echo site_url('auth/register'); ?>" data-parsley-validate="" method="POST" loader="true">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" name="first_name" placeholder="First Name" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" name="last_name" placeholder="Last Name" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" name="phone_number" placeholder="Phone Number" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email Address" required="">
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
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" name="passwordb" placeholder="Confirm Password" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="" class="auth-switch pull-right mt-10 text-muted text-thin" show=".login">Already registered? Login</a>

                                    <button type="submit" class="btn btn-primary">Create New Account</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
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
    <p class="copyright text-thin text-muted"> &copy; <?php echo date('Y') ?> <a target="_blank" href="https://bennito254.com"><?php echo get_option('site_name', 'Bennito254'); ?></a> <span>•</span> All Rights Reserved.</p>
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
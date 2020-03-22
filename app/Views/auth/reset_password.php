<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Income and Expense tracker for business and personal use.">
    <meta name="author" content="Simcy Creative">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('uploads/app/yv91yZHRY2MB84Y3vAnyGz89LYOBLDYm.png'); ?>">
    <title>Reset Password</title>
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
        <div class="login">
            <div class="auth-heading mt-15">
                <h2 class="text-center">Reset Password</h2>
                <p class="text-center">Please create a new password</p>
            </div>
            <div class="auth-form">
                <?php
                if($message) {
                    ?>
                    <div class="alert alert-danger"><?php echo $message; ?></div>
                    <?php
                }
                ?>
                <form class="simcy-form" action="<?php echo current_url(); ?>" data-parsley-validate="" method="POST" loader="true">
                    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>New Password</label>
                                <input type="password" class="form-control" name="new" placeholder="Password" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Confirm New Password</label>
                                <input type="password" class="form-control" name="new_confirm" placeholder="Password" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div>
                        </div>
                    </div>
                </form>
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
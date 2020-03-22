<div class="splash-container">
    <div class="card ">
        <div class="card-header text-center"><a href="#"><img class="logo-img" src="<?php echo base_url('assets/images/logo.png'); ?>" alt="logo"></a><span class="splash-description">Please enter your user information.</span></div>
        <div class="card-body">
            <?php
            if($message) {
                ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
                <?php
            }
            ?>
            <form method="POST" action="<?php echo site_url('auth/login'); ?>">
                <div class="form-group">
                    <input class="form-control form-control-lg" name="identity" id="username" value="<?php echo old('identity'); ?>" type="text" placeholder="Your Email" autocomplete="off">
                </div>
                <div class="form-group">
                    <input class="form-control form-control-lg" name="password" id="password" type="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <label class="custom-control custom-checkbox">
                        <input class="custom-control-input" name="remember" type="checkbox"><span class="custom-control-label">Remember Me</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
            </form>
        </div>
        <div class="card-footer bg-white p-0  ">
            <div class="card-footer-item card-footer-item-bordered">
                <a href="<?php echo site_url('auth/create'); ?>" class="footer-link">Create An Account</a></div>
            <div class="card-footer-item card-footer-item-bordered">
                <a href="<?php echo site_url('auth/forgot-password'); ?>" class="footer-link">Forgot Password</a>
            </div>
        </div>
    </div>
</div>
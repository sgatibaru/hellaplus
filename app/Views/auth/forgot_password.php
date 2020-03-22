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
            <form method="POST" action="<?php echo current_url(); ?>">
                <div class="form-group">
                    <input class="form-control form-control-lg" name="identity" id="username" value="<?php echo old('identity'); ?>" type="text" placeholder="Your Email" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Send Recovery E-Mail</button>
            </form>
        </div>
    </div>
</div>
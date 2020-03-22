<?php
include('header.php');
?>
    <div class="login">
        <div class="auth-heading mt-15">
            <h2>Hello there, welcome to Hellaplus</h2>
        </div>
        <div class="auth-form">
            <p>Hellaplus is a dashboard to manage M-Pesa Shortcodes through the Daraja API.</p>
            <h3>M-Pesa API</h3>
            <p>
                To use this dashboard, you <b>must</b> have a Live API application.<br/>
                Some features are dependent on your Organization Portal's username and password. However, it can work without them.
            </p>
            <p>To continue with the installation, have the following ready:</p>
            <ol>
                <li>A database server (it's mostly localhost)</li>
                <li>A database (Empty database is preferred)</li>
                <li>Database Username</li>
                <li>Database Password</li>
                <li>E-Mail Address</li>
            </ol>
            <p>Click the button below when ready.</p>
            <a class="btn btn-primary" href="database.php">Continue <i class="mdi mdi-arrow-right"></i> </a>
        </div>
    </div>
<?php
include('footer.php');
?>
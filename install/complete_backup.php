<?php
// Created by Bennito254 (https://www.bennito254.com)
if(file_exists('../env.php')){
    header("Location: ..");
}
error_reporting(0);
session_start();
function generateRandomString($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!-+=@#$%^&*.,:';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (!isset($_SESSION['database'])) {
    header("Location: database.php");
}
if (!isset($_SESSION['user'])) {
    header("Location: user.php");
}
//$password = 'somepass';
//$password = password_hash($password, PASSWORD_BCRYPT, ['cost'=>12]);

$conn = mysqli_connect($_SESSION['database']['server'], $_SESSION['database']['username'], $_SESSION['database']['password'], $_SESSION['database']['database']);
if (mysqli_connect_errno()) {
    header("Location: database.php");
    exit;
}
$password = password_hash($_SESSION['user']['password'], PASSWORD_BCRYPT, ['cost' => 12]);
$time = time();
$prefix = $_SESSION['database']['prefix'] ? $_SESSION['database']['prefix'] : 'prefix_';
$sql = <<<EOL
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
DROP TABLE IF EXISTS `{$prefix}businesses`;
CREATE TABLE `{$prefix}businesses` (
  `id` int(11) NOT NULL,
  `name` varchar(254) NOT NULL,
  `shortcode` int(10) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'C2B',
  `consumer_key` varchar(254) NOT NULL,
  `consumer_secret` varchar(254) NOT NULL,
  `initiator_username` varchar(254) NOT NULL,
  `initiator_password` varchar(254) NOT NULL,
  `api_setup` int(1) NOT NULL DEFAULT 0,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `{$prefix}customers`;
CREATE TABLE `{$prefix}customers` (
  `id` int(11) NOT NULL,
  `fname` varchar(60) DEFAULT NULL,
  `mname` varchar(60) DEFAULT NULL,
  `lname` varchar(60) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `{$prefix}groups`;
CREATE TABLE `{$prefix}groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{$prefix}groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User');

DROP TABLE IF EXISTS `{$prefix}login_attempts`;
CREATE TABLE `{$prefix}login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$prefix}logs`;
CREATE TABLE `{$prefix}logs` (
  `id` int(11) NOT NULL,
  `shortcode` varchar(15) NOT NULL,
  `status` varchar(10) DEFAULT NULL,
  `info` text NOT NULL,
  `actual_data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `{$prefix}options`;
CREATE TABLE `{$prefix}options` (
  `id` int(11) NOT NULL,
  `meta_parent` varchar(254) DEFAULT NULL,
  `meta_key` varchar(254) NOT NULL,
  `meta_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `{$prefix}transactions`;
CREATE TABLE `{$prefix}transactions` (
  `id` int(11) NOT NULL,
  `shortcode` int(10) NOT NULL,
  `date` varchar(15) NOT NULL,
  `trans_id` varchar(30) DEFAULT NULL,
  `trans_amount` varchar(10) DEFAULT NULL,
  `ref_number` varchar(30) DEFAULT NULL,
  `org_balance` varchar(30) DEFAULT NULL,
  `thirdparty_id` varchar(30) DEFAULT NULL,
  `msisdn` varchar(30) DEFAULT NULL,
  `fname` varchar(254) DEFAULT NULL,
  `mname` varchar(254) DEFAULT NULL,
  `lname` varchar(254) DEFAULT NULL,
  `trans_time` varchar(30) DEFAULT NULL,
  `trans_type` varchar(50) NOT NULL DEFAULT 'income'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `{$prefix}users`;
CREATE TABLE `{$prefix}users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$prefix}users_groups`;
CREATE TABLE `{$prefix}users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `{$prefix}businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shortcode` (`shortcode`);

ALTER TABLE `{$prefix}customers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{$prefix}groups`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{$prefix}login_attempts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{$prefix}logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{$prefix}options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `meta_key` (`meta_key`);

ALTER TABLE `{$prefix}transactions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{$prefix}users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_email` (`email`),
  ADD UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  ADD UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  ADD UNIQUE KEY `uc_remember_selector` (`remember_selector`);

ALTER TABLE `{$prefix}users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

ALTER TABLE `{$prefix}businesses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$prefix}customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$prefix}groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `{$prefix}login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$prefix}logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$prefix}options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$prefix}transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$prefix}users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `{$prefix}users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  
ALTER TABLE `{$prefix}users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `{$prefix}groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;
EOL;
$sql = str_replace(array("\r", "\n"), '', $sql);
//echo $sql;
//exit;
$SUCCESS = TRUE;
if (!mysqli_multi_query($conn, $sql)) {
    $SUCCESS = FALSE;
    $ERROR = "Failed to set up database: " . mysqli_error($conn);
} else {
    //Create the user
    mysqli_close($conn);
    $conn = mysqli_connect($_SESSION['database']['server'], $_SESSION['database']['username'], $_SESSION['database']['password'], $_SESSION['database']['database']);
    $sql = "INSERT INTO `{$prefix}users` (ip_address, username, password, email, created_on, first_name, last_name) VALUES ('127.0.0.1', '".mysqli_escape_string($conn, $_SESSION['user']['email'])."', '".mysqli_escape_string($conn, $password)."', '".mysqli_escape_string($conn, $_SESSION['user']['email'])."', '{$time}', '".mysqli_escape_string($conn, $_SESSION['user']['fname'])."', '".mysqli_escape_string($conn, $_SESSION['user']['lname'])."');";
    $sql .= "INSERT INTO `{$prefix}users_groups` (user_id, group_id) VALUES (1, 1);";
    if(!mysqli_multi_query($conn, $sql)) {
        $SUCCESS = FALSE;
        $ERROR = "Failed to set up Admin Account: " . mysqli_error($conn);
    } else {
        $url = $_SESSION['user']['url'];
        $contents = "<?php if (!defined('BASEPATH')) exit('No direct access'); ?>
CI_ENVIRONMENT = production
app.baseURL = ".$url."
app.indexPage =
encryption.key = ".generateRandomString(32)."

database.default.hostname = ".$_SESSION['database']['server']."
database.default.database = ".$_SESSION['database']['database']."
database.default.username = ".$_SESSION['database']['username']."
database.default.password = ".$_SESSION['database']['password']."
database.default.DBDriver = MySQLi
database.default.DBPrefix = ".$prefix;
        if(file_put_contents('../env.php', $contents, LOCK_EX)) {
            $WRITE = TRUE;
        } else {
            $WRITE = FALSE;
        }
    }
}

include "header.php";

if ($SUCCESS) {
    session_destroy();
    ?>
    <div class="login">
        <div class="auth-heading mt-15">
            <h2 class="text-center">Setup Complete</h2>
        </div>
        <div class="auth-form">
            <h3>Congratulations! Your dashboard is now installed!</h3>
            <?php
            if(!$WRITE) {
                ?>
                <div class="alert alert-warning">Failed to write to configuration file. Please copy the following and paste it in <code><?php echo dirname(dirname(__FILE__)).'/env.php'; ?></code></div>
                <textarea class="form-control" readonly="readonly" rows="15"><?php echo $contents; ?></textarea>
                <?php
            }
            ?>
            <p>
                Click the button below to login using the details you have set up.
            </p>
            <div>
                <a class="btn btn-lg btn-primary" href="<?php echo $url; ?>">Proceed to Login</a>
            </div>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="login">
        <div class="auth-heading mt-15">
            <h2 class="text-center">Setup Failed</h2>
        </div>
        <div class="auth-form">
            <p>
                A problem occured
            </p>
            <div class="alert alert-danger">
                <?php echo $ERROR; ?>
            </div>
            <a class="btn btn-lg btn-danger" href="">Retry</a>
            <a class="btn btn-lg btn-success" href="database.php">Start Over</a>
        </div>
    </div>
    <?php
}

include 'footer.php';
?>

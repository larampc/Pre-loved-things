<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$dbh = get_database_connection();
$email = $_POST["email"];

if (!User::verify_email($dbh, $email)) {
    $session->add_message("error", "Invalid email");
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

$recovery_code = rand(pow(10, 3), pow(10, 4)-1);
User::add_recovery_code($dbh, $email, $recovery_code);
$msg = "Open this link to recover your password and enter the code.\nLink: " . "http://".$_SERVER['HTTP_HOST']."/pages/set_new_password.php\n Recovery code: ". $recovery_code;
$msg = wordwrap($msg,70);
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

mail($email, "Password recovery Pre-loved Things", $msg);

$session->add_message("success", "Recovery email sent");
header('Location: ' . $_SERVER['HTTP_REFERER']);
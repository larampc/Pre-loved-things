<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$dbh = get_database_connection();
$email = $_POST["email"];

if (!User::user_sent_email($dbh, $email)) {
    $session->add_message("error", "No email was sent to this account");
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

if (!User::user_check_code($dbh, $email, $_POST["code"])) {
    $session->add_message("error", "Invalid code");
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

if (User::user_check_password($dbh, $email, $_POST["password"])) {
    $session->add_message("error", "You can't use the same password");
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

User::change_password($dbh, $email, $_POST["password"]); {
    $session->add_message("success", "Your password has been changed");
}
User::remove_code($dbh, $email);

$user = User::verify_user($dbh, $email, $_POST['password']);
var_dump($email);
var_dump($user);

$session->set_id($user->user_id);
if($user->role === "admin") $session->set_admin();
$session->set_currency(User::get_currency($dbh, $user->user_id));

if ($session->has_items_cart()) User::add_to_cart($dbh, $session->get_cart(), $user->user_id);

$session->add_message('success', 'Login successful!');

header('Location: ../pages' );
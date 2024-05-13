<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if ($_SESSION['csrf'] !== $_POST['csrf']) {
    $session->addMessage('error', 'Illegitimate request.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}


require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

$checkout = isset($_GET['checkout']);
$email_available = !User::verify_email($dbh, $_POST['email']);
$username_available = !User::verify_username($dbh, $_POST['username']);
$message = isset($_GET['user']);

if ($email_available && $username_available) {

    $session->setId(User::register_user($dbh, $_POST['password'], $_POST['username'], $_POST['email'], $_POST['name'], $_POST['phone'], $session->getCurrency())->user_id);
    if ($session->hasItemsCart()) User::add_to_cart($dbh, $session->getCart(), $session->getId());

    $session->addMessage('success', 'Account successfully created.');
    header('Location: '. ($checkout? '../pages/checkout.php':($message? '../pages/inbox.php?user_id='.$_GET['user'].'&item_id='.$_GET['item'] : '../index.php')));
}
else {
    $session->addMessage('error', $email_available? 'Username already taken' : 'Email already registered.');
    header('Location: ../pages/login.php'. ($checkout? '?checkout':($message? '?user='.$_GET['user'].'&item='.$_GET['item']:'')));
}

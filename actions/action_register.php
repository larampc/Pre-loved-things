<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$checkout = isset($_GET['checkout']);
if (!User::verify_email($dbh, $_POST['email'])) {
    $session->setId(User::register_user($dbh, $_POST['password'], $_POST['username'], $_POST['email'], $_POST['name'], $_POST['phone']));
    if ($session->hasItemsCart()) User::add_to_cart($dbh, $session->getCart(), $session->getId());
    header('Location: ' . ($checkout ? '../pages/checkout.php':'../index.php'));
}
else header('Location: ../pages/login.php' . ($checkout ? "?checkout" : ""));

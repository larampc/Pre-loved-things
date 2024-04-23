<?php

declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$checkout = isset($_GET['checkout']);
if (!User::verify_email($dbh, $_POST['email'])) {
    $_SESSION['user_id'] = User::register_user($dbh, $_POST['password'], $_POST['username'], $_POST['email'], $_POST['name'], $_POST['phone']);
    if ($_SESSION['cart']) User::add_to_cart($dbh, $_SESSION['cart'], $_SESSION['user_id']);
    header('Location: ' . ($checkout ? '../pages/checkout.php':'../index.php'));
}
else header('Location: ../pages/login.php' . ($checkout ? "?checkout" : ""));

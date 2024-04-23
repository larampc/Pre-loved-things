<?php

declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../database/users.db.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$checkout = isset($_GET['checkout']);
if (!verify_email($dbh, $_POST['email'])) {
    $_SESSION['user_id'] = register_user($dbh, $_POST['password'], $_POST['email'], $_POST['name'], $_POST['phone']);
    if ($_SESSION['cart']) add_to_cart($dbh, $_SESSION['cart'], $_SESSION['user_id']);
    header('Location: ' . ($checkout ? '../pages/checkout.php':'../index.php'));
}
else header('Location: ../pages/login.php' . ($checkout ? "?checkout" : ""));

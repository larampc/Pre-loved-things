<?php

declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../database/users.db.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

if (isset($_SESSION['user_id'])) add_cart($dbh, (int)$_SESSION['user_id'], (int)$_GET['item']);
else {
    if ($_SESSION['cart'] === null) {
        $_SESSION['cart'] = array();
    }
    $id = (int)$_GET['item'];
    $_SESSION['cart'][] = $id;
    $_SESSION['cart'] = array_unique($_SESSION['cart']);
}

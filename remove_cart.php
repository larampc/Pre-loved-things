<?php

declare(strict_types=1);

session_start();

require_once('database/users.db.php');
require_once('database/item.class.php');


$dbh = get_database_connection();
$item = Item::get_item($dbh, (int)$_GET['item']);
if (isset($_SESSION['user_id'])) remove_cart($dbh, (int)$_SESSION['user_id'], (int)$_GET['item']);
else unset($_SESSION['cart'][array_search($_GET['item'], $_SESSION['cart'])]);

echo json_encode($item);
<?php

declare(strict_types=1);

session_start();

require_once('database/users.db.php');
require_once('database/item.class.php');


$dbh = get_database_connection();
remove_cart($dbh, (int)$_SESSION['user_id'], (int)$_GET['item']);
$items = Item::get_cart_items_test($dbh, $_SESSION['user_id']);

echo json_encode($items);
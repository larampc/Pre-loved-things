<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');


$dbh = get_database_connection();
$item = Item::get_item($dbh, $_GET['item']);
if (isset($_SESSION['user_id'])) User::remove_cart($dbh, $session->getId(), $_GET['item']);
else unset($_SESSION['cart'][array_search($_GET['item'], $_SESSION['cart'])]);

echo json_encode($item);
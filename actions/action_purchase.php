<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$address = $_POST["address"];
$city = $_POST["city"];
$postalCode = $_POST["postalCode"];

$items = User::get_cart_items_from_user($dbh, $session->getId(), $session->getItemCheckout());
Item::update_item_sold($dbh, $items);
Item::remove_cart_favorite($dbh, $items);
$purchase = Item::register_purchase($dbh, $session->getId(), $items,$address, $city, $postalCode);

header('Location: ../pages/track_item.php?purchase='. ($purchase));
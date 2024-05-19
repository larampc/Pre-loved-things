<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/track_item.class.php');

$page = $_GET['page'];
$nav = $_GET['nav'];
$user = $_GET['user'] ? :$session->getId();
$dbh = get_database_connection();
$items = array();

if ($nav == "my") $items = Item::get_user_items($dbh, $user, intval($page));
else if ($nav == "purchases") $items = TrackItem::get_pending_purchases_items($dbh, $user, intval($page));
else if ($nav == "sales") $items = TrackItem::get_pending_sales_items($dbh, $user, intval($page));
else if ($nav == "sold") $items = TrackItem::get_sold_items($dbh, $user, intval($page));
else if ($nav == "purchased") $items = TrackItem::get_purchased_items($dbh, $user, intval($page));

echo json_encode($items);
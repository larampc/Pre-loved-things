<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/track_item.class.php');

$nav = $_GET['nav'];
$user = $_GET['user'] ? :$session->get_id();
$dbh = get_database_connection();
$items = array();

if ($nav == "my") $items = ceil(Item::get_max_user_items($dbh, $user)/6);
else if ($nav == "purchases") $items = ceil(TrackItem::get_max_pending_purchases_items($dbh, $user)/6);
else if ($nav == "sales") $items = ceil(TrackItem::get_max_pending_sales_items($dbh, $user)/6);
else if ($nav == "sold") $items = ceil(TrackItem::get_max_sold_items($dbh, $user)/6);
else if ($nav == "purchased") $items = ceil(TrackItem::get_max_purchased_items($dbh, $user)/6);

echo json_encode($items);
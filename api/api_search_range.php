<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

$cat = $_GET['cat'];
$cond = $_GET['cond'];
$dbh = get_database_connection();
$itemsCat = array();

if ($cat === '') $itemsCat = Item::get_items($dbh, 18);
else {
    $values = explode(',', $cat);
    foreach ($values as $value) {
        $range = explode('-', $value);
        $items2 = Item::get_items_by_range($dbh, (int)$range[0], (int)$range[1]);
        $itemsCat = array_merge($itemsCat, $items2);
    }
}

$itemsCond = array();
if ($cond === '') $itemsCond = Item::get_items($dbh, 18);
else {
    $values = explode(',', $cond);
    foreach ($values as $value) {
        $items2 = Item::get_items_by_condition($dbh, $value);
        $itemsCond = array_merge($itemsCond, $items2);
    }
}

$res = array();
foreach ($itemsCat as $item) {
    if (in_array($item, $itemsCond)) array_push($res, $item);
}

echo json_encode($res);
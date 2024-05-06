<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

$cat = $_GET['cat'];
$tag = $_GET['tag'] ?: "";
$page = $_GET['page'];
$range = $_GET['price'];
$search = $_GET['search'];
$dbh = get_database_connection();

$checkTag = !empty($_GET['tag']);
$tagOptions = explode('-', $tag);
unset($tagOptions[0]);
$itemsTags = array();
foreach ($tagOptions as $tagOption) {
    $values = explode(',', $tagOption);
    $id = Tag::get_tag_id($dbh, $_GET['cat'], $values[0]);
    unset($values[0]);
    $optionTags = array();
    foreach ($values as $value) {
        $options = Tag::get_items_with_tags($dbh, $id, $value);
        foreach ($options as $option) {
            $optionTags[] = $option;
        }
    }
    if ($itemsTags) $itemsTags = array_intersect($itemsTags, $optionTags);
    else $itemsTags = $optionTags;
}

$rangeops = explode(',', $range);
$min = intval($rangeops[0]);
$max = intval($rangeops[1]);

$itemsCat = array();
if ($cat !== '')  {
    $values = explode(',', $cat);
    foreach ($values as $value) {
        array_push($itemsCat, Tag::get_category_id($dbh, $cat));
    }
}
else {
    $values = Tag::get_categories($dbh);
    foreach ($values as $value) {
        array_push($itemsCat, Tag::get_category_id($dbh, $value['category']));
    }
}

    $res = Item::get_filtered_items($dbh, $itemsCat, $itemsTags, intval($page), $checkTag, intval($min / User::get_currency_conversion($dbh, $session->getCurrency())), intval($max/ User::get_currency_conversion($dbh, $session->getCurrency())), $search);
    foreach ($res as $item) {
    $item->price = round($item->price * User::get_currency_conversion($dbh, $session->getCurrency()), 2);
}

echo json_encode($res);
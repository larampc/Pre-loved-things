<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$january_sold = Item::get_sell_items($dbh, "01");
$january_buy = Item::get_buy_items($dbh, "01");
$february_sold = Item::get_sell_items($dbh, "02");
$february_buy = Item::get_buy_items($dbh,"02");
$march_sold = Item::get_sell_items($dbh, "03");
$march_buy = Item::get_buy_items($dbh,"03");
$april_sold = Item::get_sell_items($dbh, "04");
$april_buy = Item::get_buy_items($dbh, "04");
$may_sold = Item::get_sell_items($dbh, "05");
$may_buy = Item::get_buy_items($dbh, "05");
$june_sold = Item::get_sell_items($dbh, "06");
$june_buy = Item::get_buy_items($dbh, "06");
$july_sold = Item::get_sell_items($dbh, "07");
$july_buy = Item::get_buy_items($dbh, "07");
$august_sold = Item::get_sell_items($dbh, "08");
$august_buy = Item::get_buy_items($dbh, "08");
$september_sold = Item::get_sell_items($dbh, "09");
$september_buy = Item::get_buy_items($dbh,"09");
$october_sold = Item::get_sell_items($dbh, "10");
$october_buy = Item::get_buy_items($dbh, "10");
$november_sold = Item::get_sell_items($dbh, "11");
$november_buy = Item::get_buy_items($dbh, "11");
$december_sold = Item::get_sell_items($dbh, "12");
$december_buy = Item::get_buy_items($dbh,"12");

$statistics = array( array("Month", "Items", "Sold/Bought"),
    array("January", $january_sold, $january_buy),
    array("February", $february_sold, $february_buy),
    array("March", $march_sold, $march_buy),
    array("April", $april_sold, $april_buy),
    array("May", $may_sold, $may_buy),
    array("June", $june_sold, $june_buy),
    array("July", $july_sold, $july_buy),
    array("August", $august_sold, $august_buy),
    array("September", $september_sold, $september_buy),
    array("October", $october_sold, $october_buy),
    array("November", $november_sold, $november_buy),
    array("December", $december_sold, $december_buy));
echo json_encode($statistics);
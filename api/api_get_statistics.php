<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$user = $_GET['user'];
$january_sold = User::get_sold_items($dbh, $user, "01");
$january_buy = User::get_bought_items($dbh, $user, "01");
$february_sold = User::get_sold_items($dbh, $user, "02");
$february_buy = User::get_bought_items($dbh, $user, "02");
$march_sold = User::get_sold_items($dbh, $user, "03");
$march_buy = User::get_bought_items($dbh, $user, "03");
$april_sold = User::get_sold_items($dbh, $user, "04");
$april_buy = User::get_bought_items($dbh, $user, "04");
$may_sold = User::get_sold_items($dbh, $user, "05");
$may_buy = User::get_bought_items($dbh, $user, "05");
$june_sold = User::get_sold_items($dbh, $user, "06");
$june_buy = User::get_bought_items($dbh,$user, "06");
$july_sold = User::get_sold_items($dbh, $user, "07");
$july_buy = User::get_bought_items($dbh, $user, "07");
$august_sold = User::get_sold_items($dbh, $user, "08");
$august_buy = User::get_bought_items($dbh, $user, "08");
$september_sold = User::get_sold_items($dbh, $user, "09");
$september_buy = User::get_bought_items($dbh, $user, "09");
$october_sold = User::get_sold_items($dbh, $user, "10");
$october_buy = User::get_bought_items($dbh, $user, "10");
$november_sold = User::get_sold_items($dbh, $user, "11");
$november_buy = User::get_bought_items($dbh, $user, "11");
$december_sold = User::get_sold_items($dbh, $user, "12");
$december_buy = User::get_bought_items($dbh, $user, "12");

$statistics = array( array("Month", "Sold", "Buy"),
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
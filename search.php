<?php
declare(strict_types = 1);

session_start();

require_once('templates/item.tpl.php');
require_once('database/item.class.php');
require_once ('database/connection.db.php');

$db = get_database_connection();
$items = Item::get_items_category($db, $_GET['category']);
draw_header("main");
draw_page_filters($items);
draw_footer();
<?php
declare(strict_types = 1);

session_start();

require_once('templates/item.tpl.php');
require_once('database/item.class.php');
require_once ('database/connection.db.php');

$db = get_database_connection();
if (empty($_GET['category']) && !empty($_GET['q'])) {
    $items = Item::get_items_by_search($db, $_GET['q']);
}
else if (!empty($_GET['category']) && empty($_GET['q'])) {
    $items = Item::get_items_category($db, $_GET['category']);
}
else if (!empty($_GET['category']) && !empty($_GET['q'])) {
    $items = Item::get_items_by_search_cat($db, $_GET['q'], $_GET['category']);
}
else {
    $items = Item::get_items($db, 18);
}
draw_header("main");
draw_page_filters($items);
draw_footer();
<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../templates/item.tpl.php');

    $db = get_database_connection();
    if (empty($_GET['category']) && !empty($_GET['q'])) {
        $items = Item::get_items_by_search($db, $_GET['q']);
    }
    else if (!empty($_GET['category']) && empty($_GET['q'])) {
        $items = Item::get_items_category($db, $_GET['category']);
    }
    else if (!empty($_GET['category']) && !empty($_GET['q'])) {
        $items = Item::get_items_by_category($db, $_GET['q'], $_GET['category']);
    }
    else {
        $items = Item::get_items($db, 18);
    }
    draw_header("search", $session);
    draw_page_filters($items);
    draw_footer();
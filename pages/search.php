<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/tags.class.php');

    require_once(__DIR__ . '/../templates/item.tpl.php');

    $db = get_database_connection();
    $categories = Tag::get_categories($db);
    if (empty($_GET['category']) && !empty($_GET['search'])) {
        $items = Item::get_items_by_search($db, $_GET['search']);
    }
    else if (!empty($_GET['category']) && empty($_GET['q'])) {
        $items = Item::get_items_category($db, $_GET['category']);
    }
    else if (!empty($_GET['category']) && !empty($_GET['search'])) {
        $items = Item::get_items_by_category($db, $_GET['search'], $_GET['category']);
    }
    else {
        $items = Item::get_items($db, 18);
    }
    draw_header("search", $session, $categories);
    draw_page_filters($items, $_GET['category'], $db);
    draw_footer();
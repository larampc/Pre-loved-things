<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../templates/item.tpl.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    $db = get_database_connection();
    $categories = Tag::get_categories($db);
    $liked_items = Item::get_most_liked_items($db, 5);
    $recent_items = Item::get_last_added_items($db, 5);
    draw_header("main", $session, $categories);
    draw_items_main($liked_items, $recent_items);
    draw_footer();

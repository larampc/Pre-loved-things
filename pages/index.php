<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../templates/item.tpl.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    $dbh = get_database_connection();

    $liked_items = Item::get_most_liked_items($dbh, 5);
    $recent_items = Item::get_last_added_items($dbh, 5);
    get_header("main", $dbh, $session);

    draw_items_main($liked_items, $recent_items);
    draw_footer();

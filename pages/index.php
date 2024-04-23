<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../templates/item.tpl.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    $db = get_database_connection();
    $items = Item::get_items($db, 3);
    draw_header("main", $session);
    draw_items_main($items);
    draw_footer();

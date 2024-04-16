<?php
    declare(strict_types = 1);

    session_start();

    require_once('templates/item.tpl.php');
    require_once('database/item.class.php');
    require_once ('database/connection.db.php');

    $db = get_database_connection();
    $items = Item::get_items($db, 3);
    draw_header("main");
    draw_items_main($items);
    draw_footer();

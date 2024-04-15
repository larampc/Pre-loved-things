<?php
    declare(strict_types = 1);

    session_start();

    require_once('templates/item.tpl.php');
    require_once('database/item.db.php');

    $db = get_database_connection();
    $items = get_items($db, 10);

    draw_header("main");
    draw_items($items);
    draw_footer();

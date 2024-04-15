<?php
    declare(strict_types = 1);

    require_once('templates/item.tpl.php');
    require_once('database/item.db.php');

    $db = get_database_connection();
    $items = get_items($db, 3);

    draw_header();
    draw_items($items);
    draw_footer();

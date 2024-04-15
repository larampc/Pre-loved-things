<?php
    declare(strict_types = 1);

    require_once('templates/common.tpl.php');
    require_once('templates/item.tpl.php');

    require_once('database/item.db.php');
    require_once('database/connection.db.php');

    $db = get_database_connection();
    $items = get_items($db);

    draw_header();
    draw_items($items);
    draw_footer();

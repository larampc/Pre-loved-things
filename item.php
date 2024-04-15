<?php

    declare(strict_types = 1);

    require_once('templates/item.tpl.php');
    require_once('templates/common.tpl.php');

    require_once('database/connection.db.php');
    require_once('database/item.db.php');

    $db = get_database_connection();
    $item = getItem($db, intval($_GET['id']));

    draw_header();
    draw_item_page($item);
    draw_footer();
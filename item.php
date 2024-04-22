<?php

    declare(strict_types = 1);

    session_start();

    require_once('templates/item.tpl.php');
    require_once('templates/common.tpl.php');

    require_once('database/item.class.php');
    require_once('database/users.db.php');
    require_once(__DIR__ . '/database/connection.db.php');


    $db = get_database_connection();
    $item = Item::get_item($db, intval($_GET['id']));

    draw_header("item");
    draw_item_page($db, $item);
    draw_footer();
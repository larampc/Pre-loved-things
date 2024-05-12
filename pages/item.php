<?php

    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../templates/item.tpl.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');

    $dbh = get_database_connection();

    $item = Item::get_item($dbh, $_GET['id']);
    get_header("item", $dbh, $session);
    draw_item_page($dbh, $item, $session);
    draw_footer();
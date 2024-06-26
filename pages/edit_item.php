<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session-> is_logged_in()) die(header('Location: /'));

    require_once(__DIR__ . '/../templates/item.tpl.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');


    $dbh = get_database_connection();

    get_header("new", $dbh, $session);
    draw_edit_item_form($dbh, $session, Item::get_item($dbh, $_POST["edit-item"]), Tag::get_categories($dbh));
    draw_footer();
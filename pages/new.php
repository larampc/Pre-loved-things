<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');

    if (!$session-> is_logged_in()) die(header('Location: /'));

    require_once(__DIR__ . '/../templates/item.tpl.php');
    $dbh = get_database_connection();
    get_header("new", $dbh, $session);
    $categories = Tag::get_categories($dbh);
    draw_new_item_form($dbh, $categories);
    draw_footer();
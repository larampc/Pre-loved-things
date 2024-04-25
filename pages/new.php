<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    if (!$session->isLoggedIn()) die(header('Location: /'));

    require_once(__DIR__ . '/../templates/item.tpl.php');
    $db = get_database_connection();
    $categories = Tag::get_categories($db);
    draw_header("new", $session, $categories);
    draw_new_item_form($db, $categories);
    draw_footer();
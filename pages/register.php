<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../templates/users.tpl.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    $checkout = isset($_GET['checkout']);
    $db = get_database_connection();
    $categories = Tag::get_categories($db);
    draw_header("register", $session, $categories);
    draw_register_form($checkout);
    draw_footer();
<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../templates/users.tpl.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    $checkout = isset($_GET['checkout']);

    $dbh = get_database_connection();

    get_header("login", $dbh, $session);
    draw_login_register_form($checkout);
    draw_footer();
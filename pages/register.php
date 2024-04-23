<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../templates/users.tpl.php');

    $checkout = isset($_GET['checkout']);

    draw_header("register", $session);
    draw_register_form($checkout);
    draw_footer();
<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../templates/users.tpl.php');

    $checkout = isset($_GET['checkout']);
    if ($checkout) $_SESSION['user_items'] = $_POST['user_items'];

    draw_header("login", $session);
    draw_login_form($checkout);
    draw_footer();
<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header('Location: /'));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/user.class.php');

    require_once(__DIR__ . '/../templates/user.tpl.php');

    $db = get_database_connection();
    draw_header("editprofile", $session);
    draw_edit_profile(User::get_user($db, $session->getId()));
    draw_footer();
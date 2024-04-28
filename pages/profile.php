<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header('Location: /'));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/comment.class.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/track_item.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');

    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');

    $dbh = get_database_connection();
    $categories = Tag::get_categories($dbh);
    draw_header("profile", $session, $categories);

    $user = User::get_user($dbh, $session->getId());
    $feedback = User::get_user_feedback($dbh, $session->getId());
    $items = Item::get_user_items($dbh, $session->getId());

    draw_user_profile($dbh, $user, $feedback, $items, $session);
    draw_footer();
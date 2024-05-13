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
    require_once(__DIR__ . '/../database/comment.class.php');
    require_once(__DIR__ . '/../database/currency.class.php');


    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');

    $dbh = get_database_connection();
    $user = User::get_user($dbh, $session->getId());
    if (!isset($user)) die(header('Location: ../actions/action_logout.php'));
    $user_currency = new Currency($dbh, $session->getCurrency());
    get_header("profile", $dbh, $session);
    $feedback = User::get_user_feedback($dbh, $session->getId());
    $items = Item::get_user_items($dbh, $session->getId());

    draw_user_profile($dbh, $user, $feedback, $items, $session, $user_currency);
    draw_footer();
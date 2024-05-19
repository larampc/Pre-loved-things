<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/track_item.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/comment.class.php');
    require_once(__DIR__ . '/../database/currency.class.php');


    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');

    $dbh = get_database_connection();
    get_header("user", $dbh, $session);

    $user_id = $_GET['user_id'];
    $user = User::get_user($dbh, $user_id);
    if (!$user) die(header('Location: /'));;
    $feedback = User::get_user_feedback($dbh, $user_id);
    $items = Item::get_user_items($dbh, $user_id);
    $user_currency = new Currency($dbh, $session->get_currency());

    draw_user_profile($dbh, $user, $feedback, $items, $session, $user_currency);
    draw_footer();
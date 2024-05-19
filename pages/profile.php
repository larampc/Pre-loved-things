<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session-> is_logged_in()) die(header('Location: /'));

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
    require_once(__DIR__ . '/../templates/common.tpl.php');

    $dbh = get_database_connection();
    $user = User::get_user($dbh, $session->get_id());
    if (!isset($user)) die(header('Location: ../actions/action_logout.php'));
    $user_currency = new Currency($dbh, $session->get_currency());
    get_header("profile", $dbh, $session);
    $feedback = User::get_user_feedback($dbh, $session->get_id());
    $items = Item::get_user_items($dbh, $session->get_id());

    draw_user_profile($user, $feedback, $session);
    draw_footer();
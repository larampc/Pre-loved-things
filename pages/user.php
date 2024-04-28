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

    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');

    $dbh = get_database_connection();
    $categories = Tag::get_categories($dbh);
    draw_header("user", $session, $categories);

    $user_id = intval($_GET['user_id']);
    $user = User::get_user($dbh, $user_id);
    $feedback = User::get_user_feedback($dbh, $user_id);
    $items = Item::get_user_items($dbh, $user_id);

    draw_user_profile($dbh, $user, $feedback, $items, $session);
    draw_footer();
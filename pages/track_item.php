<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header('Location: /'));

    require_once(__DIR__ . '/../templates/item.tpl.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/track_item.class.php');

    $db = get_database_connection();
    $categories = Tag::get_categories($db);
    $track_item = TrackItem::get_tracking_item($db, intval($_GET['item-track']));
    draw_header("new", $session, $categories);
    draw_item_tracking($track_item, $session);
    draw_footer();
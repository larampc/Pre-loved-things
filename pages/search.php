<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/tags.class.php');

    require_once(__DIR__ . '/../templates/item.tpl.php');

    $dbh = get_database_connection();
    if (!Tag::get_category_id($dbh,$_GET['category'] ?: "")) {
        $session->addMessage("error", "Invalid category");
        die(header('Location: /'));
    }
    get_header("search", $dbh, $session);
    draw_page_filters($_GET['category'] ?: "", $dbh);
    draw_footer();
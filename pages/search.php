<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/currency.class.php');


    require_once(__DIR__ . '/../templates/item.tpl.php');
    require_once(__DIR__ . '/../templates/category.tpl.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');

    $dbh = get_database_connection();
    if (!Tag::check_category($dbh, $_GET['category'] ?? "")) {
        $session->addMessage("error", "Invalid category");
        die(header('Location: /'));
    }
    get_header("search", $dbh, $session);
    $categories = Tag::get_categories($dbh);
    draw_page_filters($categories, $dbh, $session);
    draw_footer();
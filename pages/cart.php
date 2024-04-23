<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/user.class.php');

    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');

    $db = get_database_connection();
    $items = array();
    if ($session->isLoggedIn()) {
        $items = User::get_cart_items($db, $session->getId());
    }
    else {
        if ($session->hasItemsCart()) $items = Item::get_items_in_array($db, $session->getCart());
    }
    $items = Item::sort_by_user($items);
    draw_header("cart", $session);
    draw_cart($db, $items, $session);
    draw_footer();



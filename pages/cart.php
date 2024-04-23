<?php
    declare(strict_types = 1);

    session_start();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/user.class.php');

    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');

    $db = get_database_connection();
    $items = array();
    if (isset($_SESSION['user_id'])) {
        $items = User::get_cart_items($db, (int)$_SESSION['user_id']);
    }
    else {
        if ($_SESSION['cart']) $items = Item::get_items_in_array($db,$_SESSION['cart']);
    }
    $items = Item::sort_by_user($items);
    draw_header("cart");
    draw_cart($db, $items);
    draw_footer();



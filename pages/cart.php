<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/currency.class.php');


    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');
    require_once(__DIR__ . '/../templates/purchase.tpl.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');


    $dbh = get_database_connection();
    $user_currency = new Currency($dbh, $session->get_currency());

    $items = array();
    if ($session-> is_logged_in()) {
        $items = User::get_cart_items($dbh, $session->get_id());
    }
    else {
        if ($session->has_items_cart()) $items = Item::get_items_in_array($dbh, $session->get_cart());
    }
    $items = Item::sort_by_user($items);
    get_header("cart", $dbh, $session);
    draw_cart($items, $user_currency );
    draw_footer();



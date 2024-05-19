<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session-> is_logged_in()) die(header('Location: /'));
    if (!$session->get_item_checkout()) {
        $session->add_message("error", "No items where found.");
        die(header('Location: /'));
    }

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

    $items = User::get_cart_items_from_user($dbh, $session->get_id(), $session->get_item_checkout());
    if (empty($items)) {
        $session->add_message("error", "Items unavailable.");
        die(header('Location: /'));
    }
    $currency = new Currency($dbh, $session->get_currency());

    get_header("cart-checkout", $dbh, $session);
    draw_checkout_form();
    draw_checkout_summary($items, $currency);
    draw_footer();

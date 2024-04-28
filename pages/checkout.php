<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header('Location: /'));
    if (!$session->getItemCheckout()) die(header('Location: /'));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');

    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');

    $db = get_database_connection();

    $items = User::get_cart_items_from_user($db, $session->getId(), $session->getItemCheckout());
    $categories = Tag::get_categories($db);
    draw_header("cart-checkout", $session, $categories);
    draw_checkout_form();
    draw_checkout_summary($items);
    draw_footer();

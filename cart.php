<?php
    declare(strict_types = 1);

    session_start();

    require_once('database/item.class.php');
    require_once('database/users.db.php');

    require_once('templates/user.tpl.php');
    require_once('templates/item.tpl.php');


    $db = get_database_connection();
    $items = Item::get_cart_items($db, $_SESSION['username']);
    $items = Item::sort_by_user($items);
    draw_header("cart");
    draw_cart($db, $items);
    draw_footer();



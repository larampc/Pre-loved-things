<?php

    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../templates/item.tpl.php');

    $db = get_database_connection();
    $items = Item::get_favorite_items($db, $session->getId());
    draw_header("favorite", $session); ?>
        <h2>Your favorite items</h2>
    <?php if (empty($items)) { ?>
        <section class="items">
            <p>You have no favorite items yet</p>
        </section>
    <?php }
    else draw_items($items);
    draw_footer();

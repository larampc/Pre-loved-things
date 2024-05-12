<?php

    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header('Location: /'));

    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/tags.class.php');

    require_once(__DIR__ . '/../templates/item.tpl.php');

    $dbh = get_database_connection();
    $user_currency = new Currency($dbh, $session->getCurrency());

    get_header("favorite", $dbh, $session);

    $items = Item::get_favorite_items($dbh, $session->getId()); ?>
        <h2>Your favorite items</h2>
    <?php if (empty($items)) { ?>
        <section class="items">
            <p>You have no favorite items yet</p>
        </section>
    <?php }
    else draw_items($items, $user_currency);
    draw_footer();

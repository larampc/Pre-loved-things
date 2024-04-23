<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/item.class.php');

    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');

    $db = get_database_connection();
    draw_header("profile", $session);
    ?>
    <article class="userPage">
    <?php
    $user = User::get_user($db, $session->getId());
    $feedback = User::get_user_feedback($db, $session->getId());
    $items = Item::get_user_items($db, $session->getId());
    draw_profile_details($user);
    draw_user_feedback($db, $user, $feedback, $session->getId()); ?>
        <a href="new.php" class="logout">New Item</a>
    <?php draw_items($items); ?>
    </article>
<?php
    draw_footer();
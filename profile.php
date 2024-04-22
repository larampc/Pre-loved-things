<?php

    declare(strict_types=1);
    session_start();

    require_once('database/users.db.php');
    require_once ('database/item.class.php');
    require_once('templates/user.tpl.php');
    require_once('templates/item.tpl.php');
    require_once(__DIR__ . '/database/connection.db.php');


    $db = get_database_connection();
    draw_header("profile");
    ?>
    <article class="userPage">
    <?php
    $user_id = (int)$_SESSION['user_id'];
    $user = get_user($db, $user_id);
    $feedback = get_user_feedback($db, $user_id);
    $items = Item::get_user_items($db, $user_id);
    draw_profile_details($user);
    draw_user_feedback($db, $user, $feedback); ?>
        <a href="new.php" class="logout">New Item</a>
    <?php draw_items($items); ?>
    </article>
<?php
    draw_footer();
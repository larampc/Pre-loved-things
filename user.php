<?php

    declare(strict_types=1);
    session_start();

    require_once('database/users.db.php');

    require_once('templates/user.tpl.php');
    require_once('templates/common.tpl.php');
    require_once('templates/item.tpl.php');
    require_once('database/item.class.php');

    $db = get_database_connection();
    draw_header("user");
    ?>
    <article class="userPage">
    <?php
    $username = $_GET['username'];
    $user = get_user($db, $username);
    $feedback = get_user_feedback($db, $username);
    $items = Item::get_user_items($db, $username);

    draw_user_details($user);
    draw_user_feedback($db, $user, $feedback);
    draw_items($items);
    ?> </article>
<?php
    draw_footer();
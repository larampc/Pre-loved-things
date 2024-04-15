<?php

    declare(strict_types=1);
    require_once('database/connection.db.php');
    require_once('database/users.db.php');

    require_once('templates/user.tpl.php');
    require_once('templates/common.tpl.php');
    require_once('templates/item.tpl.php');

    $db = get_database_connection();
    draw_header();
    ?>
    <article class="userPage">
    <?php
    $username = $_GET['username'];
    $user = getUser($db, $username);
    $feedback = getUserFeedback($db, $username);
    $items = getUserItems($db, $username);

    drawUserDetails($user);
    drawUserfeedback($user, $feedback);
    foreach ($items as $item) {
        draw_item($item);
    }
    ?> </article>
<?php
    draw_footer();
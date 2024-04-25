<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/tags.class.php');

    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');

    $db = get_database_connection();
    $categories = Tag::get_categories($db);
    draw_header("user", $session, $categories);
    ?>
    <article class="userPage">
    <?php
    $user_id = (int)$_GET['user_id'];
    $user = User::get_user($db, $user_id);
    $feedback = User::get_user_feedback($db, $user_id);
    $items = Item::get_user_items($db, $user_id);

    draw_user_details($user);
    draw_user_feedback($db, $user, $feedback, $session);
    draw_items($items);
    ?> </article>
<?php
    draw_footer();
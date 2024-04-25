<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header('Location: /'));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/track_item.class.php');
    require_once(__DIR__ . '/../database/tags.class.php');

    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');

    $db = get_database_connection();
    $categories = Tag::get_categories($db);
    draw_header("profile", $session, $categories);
    ?>
    <article class="pfPage">
    <?php
    $user = User::get_user($db, $session->getId());
    $feedback = User::get_user_feedback($db, $session->getId());
    $items = Item::get_user_items($db, $session->getId());
    draw_profile_details($user);
    draw_user_feedback($db, $user, $feedback, $session->getId());
    draw_user_options($db, $session);
    ?> </article>
<?php
    draw_footer();
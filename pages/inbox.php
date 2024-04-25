<?php

    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header('Location: /'));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/message.class.php');
    require_once(__DIR__ . '/../database/chatroom.class.php');
    require_once(__DIR__ . '/../database/user.class.php');

    require_once(__DIR__ . '/../templates/messages.tpl.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/chatroom.tpl.php');

    $db = get_database_connection();
    $chatrooms = Chatroom::get_user_chatrooms($db, $session->getId());
    usort($chatrooms, function ($a, $b) {
        return $a->last_message->sentTime < $b->last_message->sentTime;
    });
    draw_header("inbox", $session);
    draw_user_chatrooms($chatrooms, User::get_user($db, $session->getId()));
    ?>
        <section class="chat-page">
            <article class="empty-msg-inbox">
                <i class="material-symbols-outlined">inbox</i>
                <p>Click on a message to open</p>
            </article>
        </section>
    <?php

    draw_footer();

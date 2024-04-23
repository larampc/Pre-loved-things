<?php

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/chatroom.class.php');
require_once(__DIR__ . '/../database/user.class.php');

require_once(__DIR__ . '/../templates/messages.tpl.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/chatroom.tpl.php');

session_start();

$db = get_database_connection();
$user_id = (int)$_SESSION['user_id'];
$chatrooms = Chatroom::get_user_chatrooms($db, $user_id);

draw_header("inbox");
draw_user_chatrooms($chatrooms, User::get_user($db, $user_id));
?>
    <section class="chat-page">
        <header class="message-header">
            <figure class="item-info">
                <img src="../images/flower.png" alt="item image" class="item-msg-img">
                <figcaption>Item</figcaption>
            </figure>
            <aside class="user-info">
                <p>User Name</p>
                <img src="../images/profile.png" alt="addressee profile image" class="addressee-img">
            </aside>
        </header>

        <article class="msg-inbox">
            <section class="scroll">
                <div class="messages">
                    <?php draw_sent_message() ?>
                    <?php draw_received_message() ?>
                    <?php draw_sent_message() ?>
                    <?php draw_received_message() ?>
                    <?php draw_sent_message() ?>
                    <?php draw_sent_message() ?>
                    <?php draw_sent_message() ?>
                    <?php draw_sent_message() ?>
                    <?php draw_received_message() ?>
                    <?php draw_sent_message() ?>
                    <?php draw_sent_message() ?>

                </div>
            </section>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Write message...">
                <button type="button" class="send-icon">
                    <i class="material-symbols-outlined-filled-color">send</i>
                </button>
            </div>
        </article>
    </section>
<?php

    draw_footer();

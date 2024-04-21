<?php

require_once('database/connection.db.php');
require_once ('database/message.class.php');
require_once ('database/chatroom.class.php');
require_once ('database/user.class.php');

require_once('templates/messages.tpl.php');
require_once('templates/common.tpl.php');
require_once ('templates/chatroom.tpl.php');

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
                <img src="images/flower.png" alt="item image" class="item-msg-img">
                <figcaption>Item</figcaption>
            </figure>
            <aside class="user-info">
                <img src="images/profile.png" alt="addressee profile image" class="addressee-img">
                <p>User Name</p>
            </aside>
        </header>

        <article class="msg-inbox">
            <?php draw_received_message() ?>
            <?php draw_sent_message() ?>
            <?php draw_received_message() ?>
            <?php draw_sent_message() ?>
            <?php draw_sent_message() ?>
            <?php draw_sent_message() ?>
            <?php draw_sent_message() ?>
        </article>
        <footer class="msg-bottom">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Write message...">
                <button type="button" class="send-icon">
                    <i class="bi bi-send"></i>
                </button>
            </div>
        </footer>
    </section>
<?php

    draw_footer();

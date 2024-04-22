<?php
declare(strict_types = 1);
function draw_user_chatrooms(array $chatrooms, User $from) : void { ?>
    <section class="chat-inbox">
        <h2>Inbox</h2>
        <?php foreach($chatrooms as $chatroom) {
            $to = $chatroom->seller == $from ? $chatroom->buyer : $chatroom->seller;
            draw_small_chatroom($chatroom, $from, $to);
        } ?>
    </section>
    <?php
}
function draw_small_chatroom(Chatroom $chatroom, User $from, User $to) : void { ?>
    <div class="chat">
        <img src=<?= "images/" . $to->photoPath?> />
        <div class="chat-content">
            <h4><?= $to->name?>></h4>
            <p> <?= $chatroom->last_message->message ?></p>
        </div>
        <p><?=$chatroom->unread_message_count?></p>
    </div>
<?php }
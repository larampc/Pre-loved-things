<?php
declare(strict_types = 1);
function draw_user_chatrooms(array $chatrooms, User $from) : void { ?>
    <script src="../scripts/chatroom.js" defer></script>
    <section class="chat-inbox">
        <h2>Inbox</h2>
        <section class="chat-rooms">
            <?php foreach($chatrooms as $chatroom) {
                $to = $chatroom->seller == $from ? $chatroom->buyer : $chatroom->seller;
                draw_small_chatroom($chatroom, $from, $to);
            } ?>
            <div class="inbox-end">Looks like there are no more messages</div>
        </section>
    </section>
    <?php
}
function draw_small_chatroom(Chatroom $chatroom, User $from, User $to) : void { ?>
    <div class="chat" id=<?= "chat" . $chatroom->chatroomId ?>>
        <img src=<?= "../uploads/profile_pics/" . $to->photoPath?> />
        <div class="chat-content">
            <h4><?= $to->name?></h4>
            <p> <?php echo $chatroom->last_message->message ?? " " ?></p>
        </div>
        <?php if ($chatroom->unread_message_count !== 0) {?>
            <p id="message-count"><?=$chatroom->unread_message_count?></p>
        <?php } ?>
    </div>
<?php }
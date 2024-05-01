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
    <div class="chat" id="caht<?=$chatroom->chatroomId ?>">
        <img src="../uploads/profile_pics/<?=$to->image?>.png" />
        <div class="chat-content">
            <h4><?= $to->name?></h4>
            <p> <?php echo $chatroom->last_message->message ?? " " ?></p>
        </div>
        <?php if ($chatroom->unread_message_count !== 0) {?>
            <p id="message-count"><?=$chatroom->unread_message_count?></p>
        <?php } ?>
    </div>
<?php }

function draw_big_chatroom(Chatroom $chatroom, User $to, array $messages){ ?>
    <section class="chat-page" id="chat-page<?=$chatroom->chatroomId?>">
        <header class="message-header">
            <aside class="item-info">
                <a href="../pages/item.php?id=<?=$chatroom->item->id?>"><img class="item-msg-img" alt="item image" src="../uploads/thumbnails/<?=$chatroom->item->mainImage?>.png"></a>
                <a href="../pages/item.php?id=<?=$chatroom->item->id?>"><p><?=$chatroom->item->name?></p></a>
            </aside>
            <aside class="user-info">
                <a href="../pages/user.php?user_id=<?=$to->user_id?>"><img class="addressee-img" alt="addressee profile image" src="../uploads/profile_pics/<?=$to->image?>.png"></a>
                <a href="../pages/user.php?user_id=<?=$to->user_id?>"><p><?=$to->name?></p></a>
            </aside>
        </header>
        <article class="msg-inbox">
            <section class="scroll">
                <?php foreach($messages as $message) {
                    if($message->sender === $to) draw_received_message($message);
                    else draw_sent_message($message);
                }?>
            </section>
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Write message...">
                <button type="button" class="send-icon"><i class="material-symbols-outlined filled-color">send</i>
                </button>
            </div>
        </article>
    </section>
<?php }
function draw_temporary_chatroom(User $to, Item $item) { ?>
    <section class="chat-page temporary" id="<?=$to->user_id?>&<?=$item->id?>">
        <header class="message-header">
            <aside class="item-info">
                <a href="../pages/item.php?id=<?=$item->id?>"><img class="item-msg-img" alt="item image" src="../uploads/thumbnails/<?=$item->mainImage?>.png"></a>
                <a href="../pages/item.php?id=<?=$item->id?>"><p><?=$item->name?></p></a>
            </aside>
            <aside class="user-info">
                <a href="../pages/user.php?user_id=<?=$to->user_id?>"><img class="addressee-img" alt="addressee profile image" src="../uploads/profile_pics/<?=$to->image?>.png"></a>
                <a href="../pages/user.php?user_id=<?=$to->user_id?>"><p><?=$to->name?></p></a>
            </aside>
        </header>
        <article class="msg-inbox">
            <section class="scroll">
            </section>
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Write message...">
                <button type="button" class="send-icon"><i class="material-symbols-outlined filled-color">send</i>
                </button>
            </div>
        </article>
    </section>
<?php }

function draw_empty_chatroom() { ?>
    <section class="chat-page">
        <article class="empty-msg-inbox">
            <i class="material-symbols-outlined">inbox</i>
            <p>Click on a message to open</p>
        </article>
    </section>
<?php }
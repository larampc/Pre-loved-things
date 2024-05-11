<?php
    declare(strict_types = 1);
    function draw_received_message(Message $message) : void{
        date_default_timezone_set('Europe/Lisbon');
        ?>
        <div class="received-message">
            <p class="received-msg-text"> <?= $message->message ?> </p>
            <time datetime="<?=$message->sentTime?>"><?= date('G:i | d/m',$message->sentTime) ?></time>
        </div>
   <?php }

    function draw_sent_message(Message $message) : void {
        date_default_timezone_set('Europe/Lisbon');
        ?>
        <div class="sent-message">
            <p class="sent-msg-text"><?= $message->message ?></p>
            <time datetime="<?=$message->sentTime?>"><?= date('G:i | d/m',$message->sentTime) ?></time>
        </div>
<?php }


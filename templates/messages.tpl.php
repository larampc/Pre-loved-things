<?php
    declare(strict_types = 1);
    function draw_chat() : void { ?>
        <div class="chat">
            <h4>User profile</h4>
            <img src="images/profile.png">
            <p>heyyy</p>
        </div>
    <?php }
    function draw_received_message() : void{ ?>
        <div class="received-message">
            <p class="received-msg-text">
Hi!! This is a message from Riya. Lorem ipsum dolor sit amet consectetur adipisicing elit.
Non quas nemo eum, earum sunt, nobis similique quisquam eveniet pariatur commodi modi voluptatibus
                        iusto omnis harum illum iste distinctio expedita illo!
            </p>
            <time datetime="2024-07-24T18:06">18:06 PM | July 24</time>
        </div>
   <?php }

    function draw_sent_message() : void { ?>
        <div class="sent-message">
            <p class="sent-msg-text">
                Hi!! This is a message from Riya. Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Non quas nemo eum, earum sunt, nobis similique quisquam eveniet pariatur commodi modi voluptatibus
                iusto omnis harum illum iste distinctio expedita illo!
            </p>
            <time datetime="2024-07-24T18:06">18:06 PM | July 24</time>
        </div>
<?php }


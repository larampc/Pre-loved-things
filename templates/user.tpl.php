<?php
declare(strict_types=1);
function draw_user_details($user) { ?>
    <section class="user">
        <img src="images/profile.png" class="profile-pic" alt="profile picture">
        <div class="user-details">
            <h2 class="username"><?=$user['username']?></h2>
            <p class="phone"><?=$user['phone']?></p>
            <p class="email"><?=$user['email']?></p>
        </div>
    </section>
    <?php
}

function draw_user_feedback($user, $feedback) { ?>
    <section class="feedback">
        <h2>Feedback</h2>
            <div class ="comment-box">
                <?php
                    foreach ($feedback as $comment) { ?>
                    <article class="comment">
                        <img src="images/profile.png" class="profile-pic" alt="profile picture">
                        <p class="uname"><?=$comment['userc']?></p>
                        <time><?=$comment['date']?></time>
                        <p class="content"><?=$comment['text']?></p>
                    </article>
                        <?php
                    } ?>
            </div>
        <p>+ Add your review</p>
    </section>
<?php
}
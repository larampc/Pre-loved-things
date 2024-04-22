<?php
declare(strict_types=1);
require_once('templates/common.tpl.php');
function draw_user_details($user) { ?>
    <section class="user">
        <img src="images/<?=$user['photoPath']?>" class="profile-pic" alt="profile picture">
        <div class="user-details">
            <h2 class="name"><?=$user['name']?></h2>
            <p class="phone"><?=$user['phone']?></p>
            <p class="email"><?=$user['email']?></p>
        </div>
    </section>
    <?php
}

function draw_edit_profile($user) { ?>
    <article class="edit-profile">
        <h2>Edit profile</h2>
        <form action="action_edit_profile.php" method="POST" enctype="multipart/form-data">
            <label for="name"> Name </label>
            <input type="text" id="name" name="name" placeholder="<?=$user['name']?>">
            <label for="email"> Email </label>
            <input type="text" id="email" name="email" placeholder="<?=$user['email']?>">
            <label for="phone"> Phone </label>
            <input type="text" id="phone" name="phone" placeholder="<?=$user['phone']?>">
            <label for="pf">Profile photo</label>
            <input type="file" id="pf" name="profilePhoto" accept="image/*">
            <input type="submit" value="Submit">
        </form>
    </article>
    <?php
}

function draw_user_feedback(PDO $db, $user, $feedback) { ?>
    <section class="feedback">
        <h2>Feedback</h2>
            <div class ="comment-box">
                <?php
                    if (empty($feedback)) { ?>
                        <p>There are no reviews for this user yet.</p>
                    <?php }
                    foreach ($feedback as $comment) { ?>
                    <article class="comment">
                        <img src="images/<?= get_user_image($db, $comment['userc'])?>" class="profile-pic" alt="profile picture">
                        <p class="uname"><?=$comment['userc']?></p>
                        <time><?=$comment['date']?></time>
                        <p class="content"><?=$comment['text']?></p>
                    </article>
                        <?php
                    } ?>
            </div>
        <?php if ($user['user_id']!=$_SESSION['user_id']) echo("<p>+ Add your review</p>"); ?>
    </section>
<?php
}

function draw_profile_details($user) {
    ?>
    <section class="user">
        <img src="images/profile.png" class="profile-pic" alt="profile picture">
        <div class="user-details">
            <h2 class="name"><?=$user['name']?></h2>
            <p class="phone"><?=$user['phone']?></p>
            <p class="email"><?=$user['email']?></p>
            <a href="action_logout.php" class="logout">Log out</a>
            <a href="edit_profile.php">Edit profile</a>
        </div>
    </section>
    <?php
}

function draw_cart(PDO $db, array $items) { ?>
    <article class="cartPage">
        <h2>Your cart</h2>
        <?php
        $user = null;
        $num_items = 0;
        $sum = 0;
        if (empty($items)) { ?>
            <p>You have no items</p>
            </article>
        <?php
        return;
        }
        foreach ($items as $item) {
            if ($user != $item->creator && $user != null) { ?>
                </article>
                    <div class="sum">
                        <p class="num-items">Number items: <?=$num_items?></p>
                        <div class="sum-price">
                            <p>Total: </p>
                            <p class="total"><?=$sum?></p>
                        </div>
                        <form class="buy-item">
                            <label>
                                <button class="Buy" type="submit">Buy now!</button>
                            </label>
                        </form>
                    </div>
                </section>
        <?php
                $num_items = 0;
                $sum = 0;
            }
            if ($user != $item->creator) { ?>
                <section class="seller">
                    <div class="seller-info">
                        <img src="images/<?=get_user_image($db, $item->creator)?>" class="profile-pic" alt="profile-photo">
                        <p><?=get_user($db, $item->creator)['name']?></p>
                    </div>
                    <article class="seller-items">
            <?php }
            draw_item($item);
            $num_items += 1;
            $sum += $item->price;
            $user = $item->creator;
        } ?>
            </article>
            <div class="sum">
                <p class="num-items">Number items: <?=$num_items?></p>
                <div class="sum-price">
                    <p>Total: </p>
                    <p class="total"><?=$sum?></p>
                </div>
                <form class="buy-item">
                    <label>
                        <button class="Buy" type="submit">Buy now!</button>
                    </label>
                </form>
            </div>
        </section>
    </article>
<?php }

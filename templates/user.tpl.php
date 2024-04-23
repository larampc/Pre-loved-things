<?php
declare(strict_types=1);
require_once(__DIR__ . '/../templates/common.tpl.php');
function draw_user_details($user) { ?>
    <section class="user">
        <img src="../images/<?=$user['photoPath']?>" class="profile-pic" alt="profile picture">
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
        <form action="../actions/action_edit_profile.php" method="POST" enctype="multipart/form-data">
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
                        <img src="../images/<?= get_user_image($db, $comment['userc'])?>" class="profile-pic" alt="profile picture">
                        <p class="uname"><?=$comment['userc']?></p>
                        <time><?=$comment['date']?></time>
                        <p class="content"><?=$comment['text']?></p>
                    </article>
                        <?php
                    } ?>
            </div>
        <?php if ($user['user_id']!=$_SESSION['user_id']) echo("<p>+ Add your review</p>"); ?>
    </section>
<?php } ?>

<?php function draw_profile_details($user) {
    ?>
    <section class="user">
        <img src="../images/profile.png" class="profile-pic" alt="profile picture">
        <div class="user-details">
            <h2 class="name"><?=$user['name']?></h2>
            <p class="phone"><?=$user['phone']?></p>
            <p class="email"><?=$user['email']?></p>
            <a href="../actions/action_logout.php" class="logout">Log out</a>
            <a href="edit_profile.php">Edit profile</a>
        </div>
    </section>
<?php } ?>

<?php function draw_cart(PDO $db, array $items) { ?>
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
                        <form class="checkout-item" action="<?=$_SESSION['user_id']?"../pages/checkout.php": "../pages/login.php?checkout"?>" method="post">
                            <input type="hidden" value="<?=$user?>" name="user_items">
                            <label>
                                <button class="checkout" type="submit">Buy now!</button>
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
                    <a href="../pages/user.php?user_id=<?=$item->creator?>" class="seller-info">
                        <img src="../images/<?=get_user_image($db, $item->creator)?>" class="profile-pic" alt="profile-photo">
                        <p><?=get_user($db, $item->creator)['name']?></p>
                    </a>
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
                <form class="checkout-item" action="<?=$_SESSION['user_id']?"../pages/checkout.php": "../pages/login.php?checkout"?>" method="post">
                    <input type="hidden" value="<?=$user?>" name="user_items">
                    <label>
                        <button class="checkout" type="submit">Buy now!</button>
                    </label>
                </form>
            </div>
        </section>
    </article>
<?php } ?>

<?php function draw_checkout_form() { ?>
    <form class="checkout">
        <button type="button" class="collapsible">Shipping information</button>
        <div class="buy-form">
            <label> Address
                <input type="text" name="address">
            </label>
            <label> City
                <input type="text" name="city">
            </label>
            <label> Postal code
                <input type="text" name="city">
            </label>
            <button type="button" class="next">Next</button>
        </div>
        <button type="button" class="collapsible">Billing information</button>
        <div class="buy-form">
            <label> Name
                <input type="text" name="address">
            </label>
            <label> NIF
                <input type="text" name="address">
            </label>
            <label> Address
                <input type="text" name="address">
            </label>
            <label> City
                <input type="text" name="city">
            </label>
            <label> Postal code
                <input type="text" name="city">
            </label>
            <button type="button" class="next">Next</button>
        </div>
        <button type="button" class="collapsible">Payment information</button>
        <div class="buy-form">
            <div class="options">
                <label> Credit card
                    <input class="option" type="radio" name="option" id="credit-card" checked>
                </label>
                <label> Mbway
                    <input class="option" type="radio" name="option" id="mbway">
                </label>
                <label> Paypal
                    <input class="option" type="radio" name="option" id="paypal">
                </label>
            </div>
            <div id="credit-card" class="payment-form">
                <label> Card number
                    <input type="text" name="card-number">
                </label>
                <label> CVC
                    <input type="text" name="cvc">
                </label>
                <label> Expiration date
                    <input type="date" name="expire">
                </label>
            </div>
            <div id="mbway" class="payment-form">
                <label> Phone number
                    <input type="text" name="phone">
                </label>
            </div>
            <button type="submit" class="confirm">Confirm payment</button>
        </div>
    </form>
<?php } ?>

<?php function draw_checkout_summary(array $items) {?>
    <div class="sum">
        <p class="num-items">Number items: <?=count($items)?></p>
        <?php
        $sum = 0;
        foreach ($items as $item) { ?>
            <div class="item-info">
                <p class="name"><?=$item->name?></p>
                <p class="price"><?=$item->price?></p>
            </div>
        <?php
            $sum +=$item->price;
        } ?>
        <div class="sum-price">
            <p>Total: </p>
            <p class="total"><?=$sum?></p>
        </div>
    </div>
<?php }

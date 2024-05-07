<?php
declare(strict_types=1);
require_once(__DIR__ . '/../templates/common.tpl.php');

function draw_user_profile(PDO $dbh, User $user, array $feedback, array $items, Session $session) { ?>
    <article class=<?php echo $session->getId() !== $user->user_id ? "userPage" : "pfPage" ?>> <?php
        draw_user_details($dbh, $user, $session);
        draw_user_feedback($user, $feedback, $session->getId());
        draw_items($dbh, $session, $items);
        if ($session->getId() === $user->user_id) draw_user_options($dbh, $session);
        ?>
    </article> <?php
}

function draw_user_details(PDO $dbh, User $user, Session $session) { ?>
    <section class="user">
        <img src="../uploads/profile_pics/<?=$user->image?>.png" class="profile-picture" alt="profile picture">
        <div class="user-details">
            <h2 class="name"><?=$user->name?></h2>
            <?php if ($session->isLoggedIn() &&  $session->getId() === $user->user_id) {?><p class="username"><?=$user->username?></p> <?php } ?>
            <p class="phone"><?=$user->phone?></p>
            <p class="email"><?=$user->email?></p>
            <?php if ($session->isLoggedIn() && $session->getId() === $user->user_id) {?>
            <a href="../actions/action_logout.php" class="logout"><i class="material-symbols-outlined bold">logout</i>Log out</a>
            <a href="../pages/edit_profile.php"><i class="material-symbols-outlined bold">edit</i>Edit profile</a>
            <?php } ?>
            <?php if ($session->isLoggedIn() && $session->getId() !== $user->user_id && User::get_user($dbh, $session->getId())->role === "admin") {?>
                <script src="../scripts/user_actions.js" defer></script>
                <div class="admin-actions">
                    <form method="post" action="../actions/action_remove_user.php" class="confirmation">
                        <button title="Remove user" type="submit" value="<?=$user->user_id?>" name="remove-user" class="remove confirm-action" ><i class="material-symbols-outlined big"> person_remove </i></button>
                    </form>
                    <form method="post" action="../actions/action_change_role.php" class="confirmation">
                        <button title="<?=$user->role=="admin"? "Demote user": "Promote user"?>" type="submit" value="<?=$user->user_id?>" name="role-user" class="role confirm-action" ><i class="material-symbols-outlined big"> <?=$user->role=="admin"? "person_off": "admin_panel_settings"?> </i></button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </section>
    <?php
}
function draw_edit_profile($user) { ?>
    <script src="../scripts/preview_image.js" defer></script>
    <article class="edit-profile">
        <h2>Edit profile</h2>
        <form action="../actions/action_edit_profile.php" method="POST" enctype="multipart/form-data">
            <label for="name"> Name </label>
            <input type="text" id="name" name="name" value="<?=$user->name?>" required>
            <label for="username"> Username </label>
            <input type="text" id="username" name="username" value="<?=$user->username?>" required>
            <label for="email"> Email </label>
            <input type="email" id="email" name="email" value="<?=$user->email?>" required>
            <label for="phone"> Phone </label>
            <input type="tel" id="phone" name="phone" value="<?=$user->phone?>" required>
            <label for="img1">Profile photo</label>
            <div class="photo-upload">
                <i class="material-symbols-outlined upload-icon">add_a_photo</i>
                <input type="file" id="img1" class="uploader" name="img1" accept="image/*" onchange="previewImage(this.id)">
            </div>
            <button type="submit">Submit</button>
        </form>
    </article>
    <?php
}

function draw_user_feedback($user, $feedback, $session_id) { ?>
    <section class="feedback">
        <h2>Feedback</h2>
            <div class ="comment-box">
                <?php
                    if (empty($feedback)) { ?>
                        <p>There are no reviews for this user yet.</p>
                    <?php }
                    foreach ($feedback as $comment) {
                        ?>
                    <article class="comment">
                        <img src="../uploads/profile_pics/<?= $comment->from->image?>.png" class="profile-picture" alt="profile picture">
                        <p class="uname"><?=$comment->from->name?></p>
                        <time><?=$comment->date?></time>
                        <section class="stars">
                            <?php for ($i = 0; $i < $comment->rating; $i++) { ?>
                                <i class="material-symbols-outlined filled"> grade </i>
                            <?php }
                            for ($i = $comment->rating; $i < 5; $i++) { ?>
                                <i class="material-symbols-outlined"> grade </i>
                            <?php } ?>
                        </section>
                        <p class="content"><?=$comment->message?></p>
                    </article>
                        <?php
                    } ?>
            </div>

        <?php if ($user->user_id!=$session_id) { ?>
            <form action="../actions/action_add_review.php?user=<?=$user->user_id?>" method="post" class="new-review">
                <input class="form-control" type="text" placeholder="Write your feedback..." name="review" required>
                <div class="star-review">
                    <input type="radio" name="stars" id="st5" value="5">
                    <label for="st5"></label>
                    <input type="radio" name="stars" id="st4" value="4">
                    <label for="st4"></label>
                    <input type="radio" name="stars" id="st3" value="3">
                    <label for="st3"></label>
                    <input type="radio" name="stars" id="st2" value="2">
                    <label for="st2"></label>
                    <input type="radio" name="stars" id="st1" value="1">
                    <label for="st1"></label>
                </div>
                <button type="submit" class="send-icon"><i class="material-symbols-outlined filled-color">prompt_suggestion</i>
                </button>
            </form>
        <?php } ?>
    </section>
<?php } ?>

<?php function draw_cart(PDO $db, array $items, Session $session) { ?>
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
                            <p class="total"><?=$sum . User::get_currency_symbol($db, $session->getCurrency())?></p>
                        </div>
                        <form class="checkout-item" action="../actions/action_checkout.php" method="post">
                            <input type="hidden" value="<?=$user->user_id?>" name="user_items">
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
                    <a href="../pages/user.php?user_id=<?=$item->creator->user_id?>" class="seller-info">
                        <img src="../uploads/profile_pics/<?= $item->creator->image?>.png" class="profile-picture" alt="profile-photo">
                        <p><?=$item->creator->name?></p>
                    </a>
                    <article class="seller-items">
            <?php }
            draw_item($db, $session, $item);
            $num_items += 1;
            $sum += round($item->price * (User::get_currency_conversion($db, $session->getCurrency())), 2);
            $user = $item->creator;
        } ?>
            </article>
            <div class="sum">
                <p class="num-items">Number items: <?=$num_items?></p>
                <div class="sum-price">
                    <p>Total: </p>
                    <p class="total"><?=$sum . User::get_currency_symbol($db, $session->getCurrency())?></p>
                </div>
                <form class="checkout-item" action="../actions/action_checkout.php" method="post">
                    <input type="hidden" value="<?=$user->user_id?>" name="user_items">
                    <label>
                        <button class="checkout" type="submit">Buy now!</button>
                    </label>
                </form>
            </div>
        </section>
    </article>
<?php } ?>

<?php function draw_checkout_form() { ?>
    <script src="../scripts/checkout.js" defer></script>
    <form class="checkout" method="post" action="../actions/action_purchase.php">
        <ul class="state">
            <li class="current">
                <button type="button" class="collapsible">Shipping information</button>
                <div class="buy-form">
                    <label> Address
                        <input type="text" name="address" required>
                    </label>
                    <label> City
                        <input type="text" name="city" required>
                    </label>
                    <label> Postal code
                        <input type="text" name="postalCode" required>
                    </label>
                    <button type="button" class="next shipping">Next</button>
                </div>
            </li>
            <li>
                <button type="button" class="collapsible">Billing information</button>
                <div class="buy-form">
                    <label> Name
                        <input type="text" name="billName">
                    </label>
                    <label> NIF
                        <input type="text" name="NIF">
                    </label>
                    <label> Address
                        <input type="text" name="billAddress">
                    </label>
                    <label> City
                        <input type="text" name="billCity">
                    </label>
                    <label> Postal code
                        <input type="text" name="billPostal">
                    </label>
                    <button type="button" class="next">Next</button>
                </div>
            </li>
            <li>
                <button type="button" class="collapsible">Payment information</button>
                <div class="buy-form">
                    <div class="options">
                        <label> Credit card
                            <input class="option" type="radio" name="option" id="credit-card" checked>
                        </label>
                        <label> Mbway
                            <input class="option" type="radio" name="option" id="mbway">
                        </label>
                    </div>
                    <div id="credit-card" class="payment-form">
                        <label> Card number
                            <input type="text" name="card-number" required>
                        </label>
                        <label> CVC
                            <input type="text" name="cvc" required>
                        </label>
                        <label> Expiration date
                            <input type="date" name="expire" required>
                        </label>
                    </div>
                    <div id="mbway" class="payment-form">
                        <label> Phone number
                            <input type="text" name="phone">
                        </label>
                    </div>
                    <button type="submit" class="confirm">Confirm payment</button>
                </div>
            </li>
        </ul>
    </form>
<?php } ?>

<?php function draw_checkout_summary(array $items, Session $session, PDO $dbh) {?>
    <div class="checkoutSum">
        <p class="num-items">Number items: <?=count($items)?></p>
        <?php
        $sum = 0;
        foreach ($items as $item) { ?>
            <div class="item-info">
                <p class="name"><?=$item->name?></p>
                <p class="price"><?=round($item->price * User::get_currency_conversion($dbh, $session->getCurrency()),2) . User::get_currency_symbol($dbh, $session->getCurrency())?></p>
            </div>
        <?php
            $sum +=round($item->price * User::get_currency_conversion($dbh, $session->getCurrency()),2);
        } ?>
        <div class="sum-price">
            <p>Total: </p>
            <p class="total"><?=$sum . User::get_currency_symbol($dbh, $session->getCurrency())?></p>
        </div>
    </div>
<?php } ?>

<?php  function draw_user_options(PDO $dbh, Session $session) { ?>
    <script src="../scripts/profileNav.js" defer></script>
    <section class="display-item">
        <a href="../pages/new.php" class="new-item"><i class="material-symbols-outlined bold">library_add</i> New item </a>
        <div class="navbar">
            <button type="button" class="navOption" onclick="openNav('my')">My items</button>
            <button type="button" class="navOption" onclick="openNav('sales')">Pending sales</button>
            <button type="button" class="navOption" onclick="openNav('purchased')">Pending purchases</button>
        </div>
        <section class="items" id="purchased">
            <?php
            $items = TrackItem::get_purchased_items($dbh, $session->getId());
            foreach ($items as $item) {
                draw_item_to_track($dbh, $item);
            } ?>
            </section>
        <section class="items" id="sales">
            <?php
            $items = TrackItem::get_selling_items($dbh, $session->getId());
            foreach ($items as $item) {
                draw_item_to_track($dbh, $item);
            } ?>
            </section>
        <section class="items" id="my">
            <?php $items = Item::get_user_items($dbh, $session->getId());
            foreach($items as $item) {
                draw_item($dbh, $session, $item);
            } ?>
        </section>
    </section>
<?php
} ?>

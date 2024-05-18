<?php
declare(strict_types=1);
require_once(__DIR__ . '/../templates/common.tpl.php');

function draw_user_profile(PDO $dbh, User $user, array $feedback, array $items, Session $session, Currency $user_currency) { ?>
    <article class=<?php echo $session->getId() !== $user->user_id ? "userPage" : "pfPage" ?>>
        <script src="../scripts/user_actions.js" defer></script>
        <?php
        draw_user_details($dbh, $user, $session);
        draw_user_feedback($dbh, $user, $feedback, $session); ?>
        <?php if ($session->getId() === $user->user_id || ($session->isLoggedIn() && $session->is_admin())) { ?>
            <div id="curve_chart"></div>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <input type="hidden" class="chart-user" value="<?=$user->user_id?>">
            <script src="../scripts/draw_chart_user.js"></script>
            <?php
            draw_user_options($dbh, $user, $session, $user_currency);
        } else draw_items($items, $user_currency);
        ?>
    </article>
<?php } ?>

<?php function draw_user_details(PDO $dbh, User $user, Session $session) { ?>
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
            <?php if ($session->is_admin()) { ?>
                    <a href="../pages/admin_page.php"><i class="material-symbols-outlined bold">manage_accounts</i></a>
                <?php }} ?>
            <?php if ($session->isLoggedIn() && $session->getId() !== $user->user_id && $session->is_admin()){?>
                <div class="admin-actions">
                    <form method="post" action="../actions/action_remove_user.php" class="confirmation">
                        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

                        <button title="Remove user" type="submit" value="<?=$user->user_id?>" name="remove-user" class="remove confirm-action" ><i class="material-symbols-outlined big"> person_remove </i></button>
                    </form>
                    <form method="post" action="../actions/action_change_role.php" class="confirmation">
                        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

                        <button title="<?=$user->role=="admin"? "Demote user": "Promote user"?>" type="submit" value="<?=$user->user_id?>" name="role-user" class="role confirm-action" >
                            <i class="material-symbols-outlined big"> <?=$user->role=="admin"? "person_off": "admin_panel_settings"?></i>
                        </button>
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
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

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
                <i class="material-symbols-outlined bolder upload-icon">add_a_photo</i>
                <input type="file" id="img1" class="uploader" name="img1" accept="image/*" onchange="previewImage(this.id)">
            </div>
            <button type="submit">Submit</button>
        </form>
    </article>
    <?php
}

function draw_user_feedback(PDO $dbh, $user, $feedback, Session $session) { ?>
    <section class="feedback">
        <div class="feedback-sum">
            <h2>Feedback</h2>
            <section class="stars">
                <?php $avg = floatval(Comment::get_user_average($dbh, $user->user_id));
                $average = round($avg);
                for ($i = 0; $i < $average; $i++) { ?>
                    <i class="material-symbols-outlined filled"> grade </i>
                <?php }
                for ($i = $average; $i < 5; $i++) { ?>
                    <i class="material-symbols-outlined"> grade </i>
                <?php } ?>
                <p><?=round($avg, 2)?> out of <?=Comment::get_number_comments($dbh, $user->user_id)?> ratings</p>
            </section>
        </div>
            <div class ="comment-box">
                <?php
                    if (empty($feedback)) { ?>
                        <p>There are no reviews for this user yet.</p>
                    <?php }
                    foreach ($feedback as $comment) {
                        ?>
                    <article class="comment">
                        <a href="../pages/user.php?user_id=<?=$comment->from->user_id ?>">
                            <img src="../uploads/profile_pics/<?= $comment->from->image?>.png" class="profile-picture" alt="profile picture">
                            <p class="uname"><?=$comment->from->name?></p>
                        </a>
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

        <?php if ($session->isLoggedIn() && $user->user_id!=$session->getId()) { ?>
            <form action="../actions/action_add_review.php?user=<?=$user->user_id?>" method="post" class="new-review">
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

                <input class="write-review" type="text" placeholder="Write your feedback..." name="review" required>
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
                <button type="submit" class="send-icon"><i class="material-symbols-outlined filled-black">send</i>
                </button>
            </form>
        <?php } ?>
    </section>
<?php } ?>

<?php function draw_cart(array $items, Currency $user_currency) { ?>
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
                            <p class="total"><?=$sum . $user_currency->symbol?></p>
                        </div>
                        <form class="checkout-item" action="../actions/action_checkout.php" method="post">
                            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
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
            draw_item($item, $user_currency);
            $num_items += 1;
            $sum += round($item->price * $user_currency->conversion, 2);
            $user = $item->creator;
        } ?>
            </article>
            <div class="sum">
                <p class="num-items">Number items: <?=$num_items?></p>
                <div class="sum-price">
                    <p>Total: </p>
                    <p class="total"><?=$sum . $user_currency->symbol?></p>
                </div>
                <form class="checkout-item" action="../actions/action_checkout.php" method="post">
                    <input type="hidden" value="<?=$user->user_id?>" name="user_items">
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
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
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
        <ul class="state">
            <li class="current">
                <button type="button" class="collapsible">Shipping information</button>
                <div class="buy-form">
                    <label> Address
                        <input type="text" name="address" required autocomplete="on">
                    </label>
                    <label> City
                        <input type="text" name="city" required autocomplete="on">
                    </label>
                    <label> Postal code
                        <input type="text" name="postalCode" required autocomplete="on">
                    </label>
                    <button type="button" class="next shipping">Next</button>
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
                            <input type="text" name="phone" autocomplete="on">
                        </label>
                    </div>
                    <button type="submit" class="confirm">Confirm payment</button>
                </div>
            </li>
        </ul>
    </form>
<?php } ?>

<?php function draw_checkout_summary(array $items, Currency $user_currency) {?>
    <div class="checkoutSum">
        <p class="num-items">Number items: <?=count($items)?></p>
        <?php
        $sum = 0;
        foreach ($items as $item) { ?>
            <div class="item-info">
                <p class="name"><?=$item->name?></p>
                <p class="price"><?=round($item->price * $user_currency->conversion,2) . $user_currency->symbol?></p>
            </div>
        <?php
            $sum +=round($item->price * $user_currency->conversion,2);
        } ?>
        <div class="sum-price">
            <p>Total: </p>
            <p class="total"><?=$sum . $user_currency->symbol?></p>
        </div>
    </div>
<?php } ?>

<?php  function draw_user_options(PDO $dbh, User $user, Session $session, Currency $user_currency) { ?>
    <script src="../scripts/profileNav.js" defer></script>
    <section class="display-item">
        <?php if ($user->user_id === $session->getId()) {?>
            <a href="../pages/new.php" class="new-item"><i class="material-symbols-outlined bold">library_add</i> New item </a>
        <?php } ?>
        <div class="navbar">
            <button type="button" class="navOption my" onclick="openNav('my')"> <?=$user->user_id === $session->getId() ? "My " : "User "?> items</button>
            <button type="button" class="navOption sales" onclick="openNav('sales')">Pending sales</button>
            <button type="button" class="navOption purchased" onclick="openNav('purchased')">Pending purchases</button>
        </div>
        <section class="items" id="purchased">
            <?php
            $items = TrackItem::get_purchased_items($dbh, $user->user_id);
            foreach ($items as $item) {
                draw_item_to_track($dbh, $item);
            } ?>
            </section>
        <section class="items" id="sales">
            <?php
            $items = TrackItem::get_selling_items($dbh, $user->user_id);
            foreach ($items as $item) {
                draw_item_to_track($dbh, $item);
            } ?>
            </section>
        <section class="items" id="my">
            <?php $items = Item::get_user_items($dbh, $user->user_id);
            foreach($items as $item) {
                draw_item($item, $user_currency);
            } ?>
        </section>
    </section>
<?php
} ?>

<?php function draw_admin_page() { ?>
    <div id="curve_chart"></div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="../scripts/draw_chart_all.js"></script>
    <script src="../scripts/admin_panel.js" defer></script>
    <div class="user-search">
        <label> Search user
            <input type="text" id="search-user" placeholder="Username or Email">
        </label>
        <section class="user-table-header">
            <p>Image</p>
            <p>Username</p>
            <p>Email</p>
            <p>Phone</p>
            <p>Sold</p>
            <p>Purchases</p>
        </section>
        <section class="user-result">
            <div class="loader-users"></div>
        </section>
    </div>
<?php }

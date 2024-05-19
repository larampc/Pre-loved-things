<?php
declare(strict_types=1);
function draw_user_profile(User $user, array $feedback, Session $session) { ?>
        <script src="../scripts/user_actions.js" defer></script>
        <script src="../scripts/profile_nav.js" defer></script>
    <?php
        draw_user_details($user, $session);
        draw_user_feedback($user, $feedback, $session); ?>
        <?php if ($session->get_id() === $user->user_id || ($session-> is_logged_in() && $session->is_admin())) { ?>
            <div id="curve_chart"></div>
            <script src="https://www.gstatic.com/charts/loader.js"></script>
            <input type="hidden" class="chart-user" value="<?=$user->user_id?>">
            <script src="../scripts/draw_chart_user.js"></script>
            <?php
            draw_user_options($user, $session);
        } else  { ?>
            <div class="display-item">
                <div class="slider-btns">
                    <i class="material-symbols-outlined notSelectable" id="prev-btn"> chevron_left </i>
                    <i class="material-symbols-outlined notSelectable" id="next-btn"> chevron_right </i>
                </div>
                <div class="items"></div>
            </div>
    <?php }
} ?>

<?php function draw_user_details(User $user, Session $session) { ?>
    <div class="user">
        <img src="../uploads/profile_pics/<?=$user->image?>.png" class="profile-picture" alt="profile picture">
        <section class="user-details">
            <h2 class="name"><?=$user->name?></h2>
            <?php if ($session-> is_logged_in() &&  $session->get_id() === $user->user_id || $session->is_admin()) {?><p class="username"><?=$user->username?></p> <?php } ?>
            <p class="phone"><?=$user->phone?></p>
            <p class="email"><?=$user->email?></p>
            <?php if ($session-> is_logged_in() && $session->get_id() === $user->user_id) {?>
            <a href="../actions/action_logout.php" class="logout"><i class="material-symbols-outlined bold">logout</i>Log out</a>
            <a href="../pages/edit_profile.php"><i class="material-symbols-outlined bold">edit</i>Edit profile</a>
            <?php if ($session->is_admin()) { ?>
                    <a href="../pages/admin_page.php"><i class="material-symbols-outlined bold">manage_accounts</i></a>
                <?php }} ?>
            <?php if ($session-> is_logged_in() && $session->get_id() !== $user->user_id && $session->is_admin()){?>
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
        </section>
    </div>
    <?php
}
function draw_edit_profile(User $user) { ?>
    <script src="../scripts/preview_image.js" defer></script>
    <article class="edit-profile">
        <h2>Edit profile</h2>
        <form action="../actions/action_edit_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

            <label for="name"> Name </label>
            <input type="text" id="name" name="name" value="<?=$user->name?>" autocomplete="on" required>
            <label for="username"> Username </label>
            <input type="text" id="username" name="username" value="<?=$user->username?>" autocomplete="on" required
                   pattern="\w+" oninvalid="this.setCustomValidity('Invalid username - can only contain letters, digits and underscores')"
                   oninput="this.setCustomValidity('')">
            <label for="email"> Email </label>
            <input type="email" id="email" name="email" value="<?=$user->email?>" autocomplete="on" required>
            <label for="phone"> Phone </label>
            <input type="tel" id="phone" name="phone" value="<?=$user->phone?>" autocomplete="on" required
                   pattern="\d{9}|\d{3}-\d{3}-\d{3}"
                   oninvalid="this.setCustomValidity('Invalid phone number - (ddddddddd or ddd-ddd-ddd)')"
                   oninput="this.setCustomValidity('')">
            <label for="img1">Profile photo</label>
            <div class="photo-upload" <?php if($user->image !== "0"){ ?> style="background-image: url(<?="../uploads/profile_pics/" . $user->image . ".png" ?>)" <?php } ?> >
                <?php if( $user->image !== "0"){ ?>
                    <input type="hidden" class="image-data" value="<?=$user->image?>" name="hiddenimg1">
                    <input type="file" id="img1" class="uploader" name="img1" accept="image/*" onchange="previewImage(this.id)">
                    <i class="material-symbols-outlined bolder delete-icon" id="delete1" onclick="removeUserImage()">delete</i>
                <?php } else { ?>
                    <i class="material-symbols-outlined bolder upload-icon">add_a_photo</i>
                    <input type="file" id="img1" class="uploader" name="img1" accept="image/*" onchange="previewImage(this.id)">
                <?php }?>
            </div>
            <button type="submit">Submit</button>
        </form>
    </article>
    <?php
}

function draw_stars ($rating) : void {
    for ($i = 0; $i < $rating; $i++) { ?>
        <i class="material-symbols-outlined filled"> grade </i>
    <?php }
    for ($i = $rating; $i < 5; $i++) { ?>
        <i class="material-symbols-outlined"> grade </i>
    <?php }
}

function draw_user_feedback($user, $feedback, Session $session) { ?>
    <div class="feedback">
        <section class="feedback-sum">
            <h2>Feedback</h2>
            <div class="stars">
                <?php $avg = 0;
                foreach ($feedback as $comment) { $avg += $comment->rating; }
                $avg /= count($feedback);
                draw_stars(round($avg));
               ?> <p><?=round($avg, 2)?> out of <?=count($feedback);?> ratings</p>
            </div>
        </section>
            <div class ="comment-box">
                <?php
                    if (empty($feedback)) { ?>
                        <p>There are no reviews for this user yet.</p>
                    <?php }
                    foreach ($feedback as $comment) {
                        ?>
                    <div class="comment">
                        <a href="../pages/user.php?user_id=<?=$comment->from->user_id ?>">
                            <img src="../uploads/profile_pics/<?= $comment->from->image?>.png" class="profile-picture" alt="profile picture">
                            <p class="uname"><?=$comment->from->name?></p>
                        </a>
                        <time><?=$comment->date?></time>
                        <div class="stars">
                            <?php draw_stars($comment->rating);?>
                        </div>
                        <p class="content"><?=$comment->message?></p>
                    </div>
                        <?php
                    } ?>
            </div>

        <?php if ($session-> is_logged_in() && $user->user_id!=$session->get_id()) { ?>
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
    </div>
<?php } ?>

<?php  function draw_user_options(User $user, Session $session) { ?>
    <div class="display-item">
        <?php if ($user->user_id === $session->get_id()) {?>
            <a href="../pages/new.php" class="new-item"><i class="material-symbols-outlined bold">library_add</i> New item </a>
        <?php } ?>
        <div class="navbar">
            <button type="button" class="navOption my" onclick="openNav('my')"> <?=$user->user_id === $session->get_id() ? "My " : "User "?> items</button>
            <button type="button" class="navOption sales" onclick="openNav('sales')">Pending sales</button>
            <button type="button" class="navOption sold" onclick="openNav('sold')">Sold</button>
            <button type="button" class="navOption purchases" onclick="openNav('purchases')">Pending purchases</button>
            <button type="button" class="navOption purchased" onclick="openNav('purchased')">Purchased</button>
        </div>
        <div class="slider-btns">
            <i class="material-symbols-outlined notSelectable" id="prev-btn"> chevron_left </i>
            <i class="material-symbols-outlined notSelectable" id="next-btn"> chevron_right </i>
        </div>
        <div class="items"></div>
    </div>
<?php
} ?>

<?php function draw_admin_page() { ?>
    <div id="curve_chart"></div>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="../scripts/draw_chart_all.js"></script>
    <script src="../scripts/admin_panel.js" defer></script>
    <div class="user-search">
        <label> Search user
            <input type="text" id="search-user" placeholder="Username or Email">
        </label>
        <header class="user-table-header">
            <p>Image</p>
            <p>Username</p>
            <p>Email</p>
            <p>Phone</p>
            <p>Sold</p>
            <p>Purchases</p>
        </header>
        <div class="user-result">
            <div class="loader-users"></div>
        </div>
    </div>
<?php }

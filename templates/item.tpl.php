<?php declare(strict_types = 1);
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../utils/logger.php');

function draw_item(Item $item, Currency $user_currency) { ?>
    <a href="../pages/item.php?id=<?= $item->id ?>" class="item">
        <img src="../uploads/thumbnails/<?=$item->mainImage?>.png" alt="item photo">
        <div class="item-info">
            <p class="name"><?=$item->name?></p>
            <p class="price"><?=round($item->price * $user_currency->conversion,2) . $user_currency->symbol?></p>
        </div>
    </a>
<?php }

function draw_items_main(array $liked_items, array $recent_items, Currency $user_currency) { ?>
    <section class="last-added">
        <h2>Last Added Items</h2>
        <a href="../pages/search.php?category=" id="show-more">Show more <i class="material-symbols-outlined">arrow_right_alt </i></a>
        <?php draw_items($recent_items, $user_currency); ?>
    </section>
    <section class="most-liked">
        <h2>Most liked</h2>
        <?php draw_items($liked_items, $user_currency); ?>
    </section>
<?php }

function draw_items(array $items, Currency $user_currency) { ?>
    <div class="items">
        <?php foreach($items as $item) {
            draw_item($item, $user_currency);
        } ?>
    </div>
<?php }
function draw_sliding_items(array $items, Currency $user_currency) { ?>
    <div class="items">
        <div class="image-slide">
            <?php if (count($items) > 1) { ?>
                <div class="slider-btns">
                    <i class="material-symbols-outlined notSelectable" id="prev-btn"> chevron_left </i>
                    <i class="material-symbols-outlined notSelectable" id="next-btn"> chevron_right </i>
                </div>
            <?php } ?>
            <div class="dots">
                <?php for($i = 0; $i < sizeof($items); $i++) { ?>
                    <div class="dot" style="opacity: 0.2;"></div>
                <?php } ?>
            </div>
            <?php foreach($items as $item) {
                ?>
                <a href="../pages/item.php?id=<?= $item->id ?>" class="slides item" id="<?=$item->id?>">
                    <img src="../uploads/thumbnails/<?=$item->mainImage?>.png" alt="item photo">
                    <div class="item-info">
                        <p class="name"><?=$item->name?></p>
                        <p class="price"><?=round($item->price * $user_currency->conversion,2) . $user_currency->symbol?></p>
                    </div>
                </a>
                <?php
            } ?>
        </div>
        <script src="../scripts/slide.js" defer></script>
    </div>
<?php } ?>

<?php function draw_item_images(array $images) { ?>
    <div class="dots">
    <?php for($i = 0; $i < sizeof($images); $i++) { ?>
        <div class="dot" style="opacity: 0.2;"></div>
    <?php } ?>
    </div>
    <?php foreach($images as $image) { ?>
        <img class="slides" src="../uploads/medium/<?=$image?>.png" alt="item">
    <?php } ?>
<?php } ?>

<?php function draw_item_page(PDO $db, Item $item, Session $session, Currency $user_currency) { ?>
    <script src="../scripts/like.js" defer></script>
    <script src="../scripts/add_cart.js" defer></script>
    <header>
        <h2><?=$item->name?></h2>
        <?php if ($item->sold === false) { ?>
            <?php if ($session->isLoggedIn() && $item->creator->user_id === $session->getId()) { ?>
                <form method="post" action="../pages/edit_item.php">
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                    <button type="submit" value="<?=$item->id?>" name="edit-item" class="edit" ><i class="material-symbols-outlined big"> edit </i></button>
                </form>
            <?php } if ($session->isLoggedIn() && $item->creator->user_id !== $session->getId()) { ?>
                <div class="like">
                    <button value="<?=$item->id?>" class="material-symbols-outlined big <?= Item::check_favorite($db, $session->getId(), $item)? "filled": ""?>"> favorite </button>
                    <p>Liked by <?=Item::get_number_likes($db, $item)?></p>
                </div> <?php } ?>
        <?php }
        if ($session->isLoggedIn() && ($session->is_admin() || $session->getId() == $item->creator->user_id)) { ?>
            <form method="post" action="../actions/action_remove_item.php" class="confirmation">
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                <script src="../scripts/user_actions.js" defer></script>
                <button title="Remove item" type="submit" value="<?=$item->id?>" name="remove-item" class="role confirm-action"><i class="material-symbols-outlined big"> delete </i>
                </button>
            </form>
        <?php } ?>
    </header>
    <div class="item-images">
        <?php if (count($item->images) > 1) { ?>
            <div class="slider-btns">
                <i class="material-symbols-outlined notSelectable" id="prev-btn"> chevron_left </i>
                <i class="material-symbols-outlined notSelectable" id="next-btn"> chevron_right </i>
            </div>
        <?php } ?>
        <div class="image-slide">
            <?php draw_item_images($item->images) ?>
        </div>
    </div>
    <script src="../scripts/slide.js" defer></script>
    <p class="description"><?= $item->description ?></p>
    <section class="purchase">
        <h2 class="price"><?= round($item->price * $user_currency->conversion,2) . $user_currency->symbol?></h2>
        <?php if ($item->sold === false) { ?>
            <div class="buy-item">
                <i class="material-symbols-outlined cart big"> local_mall </i>
                <button value="<?=$item->id?>" class="Buy"><?=($session->isLoggedIn() && $session->getId() == $item->creator->user_id) ? "You own this product" : (($session->isLoggedIn() && Item::check_cart($db, $session->getId(), $item) || ($session->hasItemsCart() && in_array($item->id, $session->getCart())))?  "Already in cart" : "Buy now!")?></button>
            </div>
        <?php } ?>
    </section>
    <?php if( $item->creator->user_id !== $session->getId()) { ?>
    <form method="get" action="../pages/inbox.php" class="send-message">
        <label>
            <button class="sendMessage-btn" type="submit">Send Message</button>
        </label>
        <input type="hidden" name="user_id" value="<?=$item->creator->user_id?>">
        <input type="hidden" name="item_id" value="<?=$item->id?>">
    </form>
    <a class="userProfile" href="../pages/user.php?user_id=<?=$item->creator->user_id?>"><?=$item->creator->name?>
        <img src="../uploads/profile_pics/<?=$item->creator->image?>.png" class="profile-picture" alt="profile picture">
    </a>
    <?php } ?>
    <ul class="item-tags">
        <?php if ($item->category) { ?>
        <li>
            Category: <?=$item->category?>
        </li> <?php } ?>
        <?php foreach($item->tags as $tag) { ?>
            <li><?= $tag['tag'] . ': ' . $tag['data']?> </li>
        <?php } ?>
    </ul>
<?php }

function draw_new_item_form(PDO $db, array $categories) { ?>
    <script src="../scripts/preview_image.js" defer></script>
    <article class="newItemPage">
        <h2>New item</h2>
        <form action="../actions/action_new_item.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <p><?=log_to_stdout("heyyy")?></p>
            <label for="iname">Item Name</label>
            <input type="text" id="iname" name="iname" placeholder="The name of your item" required>

            <label for="category">Category</label>
            <?php
                if (sizeof($categories) > 0) { ?>
            <select id="category" name="category">
                <?php
                foreach ($categories as $category) { ?>
                    <option value="<?=$category['category']?:"other"?>"><?=ucfirst($category['category'])?: "Other"?></option>
                <?php }?>
            </select>
                <?php } ?>
            <script src="../scripts/new.js" defer></script>
            <?php foreach ($categories as $category) {
                $tags = Tag::get_category_tags($db, $category['category']);
                if ($tags) { ?>
                <div class="tags <?=$category['category'] ?: "other"?>">
                    <?php
                    foreach ($tags as $tag) {
                        $options = Tag::get_tag_options($db, $category['category'], $tag['tag']);
                        if ($options) { ?>
                            <label><?=$tag['tag']?>
                                <select name="<?=$tag['id']?>">
                                    <?php if ($category['category']) { ?>
                                        <option value="">None</option>
                                    <?php }
                                foreach ($options as $option) { ?>
                                    <option value="<?=$option['value']?>"><?=$option['value']?></option>
                                <?php } ?>
                                </select>
                            </label>
                        <?php }
                        else { ?>
                            <label for="<?=$tag['id']?>"><?=$tag['tag']?></label><input type="text" id="<?=$tag['id']?>" name="<?=$tag['id']?>">
                        <?php }
                    } ?>
                </div>
            <?php }} ?>

            <label for="price">Price</label>
            <input type="number" step="0.01" id="price" name="price" placeholder="The price of your item" required>

            <label for="description" >Description</label>
            <input type="text" id="description" name="description" placeholder="Describe your item" maxlength="1000" minlength="40" required>

            <section class="item-image-uploads">
                <h4>Upload Images</h4>
                <div class="photo-upload main-photo-upload">
                    <h5>Main Image</h5>
                    <i class="material-symbols-outlined bolder upload-icon">add_a_photo</i>
                    <input type="file" id="img1" class="uploader" name="img1" accept="image/*" required onchange="previewImage(this.id)">
                </div>
                <div class="image-upload-adder">
                    <i class="material-symbols-outlined">add</i>
                </div>
            </section>
            <button type="submit" >Submit</button>
        </form>
    </article>
<?php }

function draw_edit_item_form(PDO $db, Session $session, Item $item, array $categories) { ?>
    <script src="../scripts/preview_image.js" defer></script>
    <article class="newItemPage">
        <h2>Edit item</h2>
        <form action="../actions/action_edit_item.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

            <label for="iname">Item Name</label>
            <input type="text" id="iname" name="iname" value="<?= $item->name ?>" required>
            <label for="category">category</label>
            <select id="category" name="category">
                <?php foreach ($categories as $category) { ?>
                    <option value="<?=$category['category']?:"other"?>" <?=$item->category == $category['category']? "selected":""?>><?=ucfirst($category['category'])?: "Other"?></option>
                <?php }?>
            </select>
            <script src="../scripts/new.js" defer></script>
            <?php foreach ($categories as $category) {
                $tags = Tag::get_category_tags($db, $category['category']);
                if ($tags) { ?>
                    <div class="tags <?=$category['category'] ?: "other"?> <?=$category['category']==$item->category? "visible":""?>">
                        <?php
                        foreach ($tags as $tag) {
                            $options = Tag::get_tag_options($db, $category['category'], $tag['tag']);
                            if ($options) { ?>
                                <label><?=$tag['tag']?>
                                    <select name="<?=$tag['id']?>">
                                        <?php if ($category['category']) { ?>
                                            <option value="">None</option>
                                        <?php }
                                        foreach ($options as $option) { ?>
                                            <option value="<?=$option['value']?>" <?=Tag::get_tag_value_item($db, $tag['id'], $item->id) == $option['value']? "selected": ""?>><?=$option['value']?></option>
                                        <?php } ?>
                                    </select>
                                </label>
                            <?php }
                            else { ?>
                                <label for="<?=$tag['id']?>"><?=$tag['tag']?></label><input type="text" id="<?=$tag['id']?>" name="<?=$tag['id']?>" value="<?=Tag::get_tag_value_item($db, $tag['id'], $item->id)?>">
                            <?php }
                        } ?>
                    </div>
                <?php }} ?>

            <label for="price">Price</label>
            <input type="number" step="0.01" id="price" name="price" value="<?= round($item->price * User::get_currency_conversion($db, $session->getCurrency())) ?>" required>

            <label for="description">Description</label>
            <input type="text" id="description" name="description" value="<?= $item->description ?>" maxlength="1000" minlength="40" required>

            <section class="item-image-uploads">
                <h4>Upload Images</h4>
                <div class="photo-upload main-photo-upload" style="background-image: url(<?="../uploads/thumbnails/" . $item->mainImage . ".png" ?>)">
                    <h5>Main Image</h5>
                    <input type="file" id="img1" class="uploader" name="img1" accept="image/*" required onchange="previewImage(this.id)">
                    <input type="hidden" class="image-data" value="<?=$item->mainImage?>" name="<?="hiddenimg1"?>">
                    <i class="material-symbols-outlined bolder delete-icon" id="delete1" onclick="shiftImages.bind(this)()">delete</i>
                </div>
                <?php
                    $images = $item->images;
                    for ($i = 1; $i < count( $images); $i++) { ?>
                        <div class="photo-upload" style="background-image: url(<?="../uploads/thumbnails/" . $images[$i] . ".png" ?>)">
                            <input type="file" id="<?="img" . ($i+1)?>" class="uploader" name="<?="img" . ($i+1)?>" accept="image/*" onchange="previewImage(this.id)">
                            <input type="hidden" class="image-data" value="<?=$images[$i]?>" name="<?="hiddenimg" . ($i+1)?>">
                            <i class="material-symbols-outlined bolder delete-icon" id="<?= "delete" . ($i+1)?>" onclick="shiftImages.bind(this)()">delete</i>
                        </div>
                    <?php }
                ?>
                <div class="image-upload-adder">
                    <i class="material-symbols-outlined">add</i>
                </div>
            </section>

            <button type="submit" value="<?=$item->id?>" name="edit-item">Submit</button>
        </form>
    </article>
<?php }

function draw_category_tags(PDO $dbh, $category) {
    $tags = Tag::get_category_tags($dbh, $category);
    if (!empty($tags)) { ?>
    <div class="category-<?=$category?> category-box <?=$category == ""?: "hide"?>" >
        <p><?=$category?></p>
    <?php foreach ($tags as $tag) { ?>
    <div class="options tag" id="<?=$tag['tag']?>">
        <p><?=$tag['tag']?></p>
        <?php
        $options = Tag::get_tag_options($dbh, $category, $tag['tag']);
        if ($options) {
            foreach ($options as $option) {?>
                <label><input type="checkbox" name="<?=$tag['tag']?>" value="<?=$option['value']?>" autocomplete='off'>
                    <span class="paragraph"><?=$option['value']?></span>
                </label>
            <?php }}
        else { ?>
            <label><input type="text" name="<?=$tag['tag']?>" autocomplete='off'></label>
        <?php } ?>
    </div>
    <?php } ?> </div> <?php }}

function draw_page_filters(array $categories, PDO $dbh, Session $session) { ?>
    <script src="../scripts/search.js" defer></script>
    <article class="searchPage">
            <section class="filter">
                <div class="filter_header">
                    <h2>Filters</h2>
                    <button id="close-filters" onclick="closeFilters()"><i class="material-symbols-outlined big filled">close</i></button>
                </div>
                <p>Categories
                    <?php if ($session->is_admin()) {?>
                        <a href="../pages/new_category.php" class="material-symbols-outlined">add_circle</a>
                    <?php }?>
                </p>
                <div class="categories">
                    <script src="../scripts/user_actions.js" defer></script>
                    <?php foreach ($categories as $category) {
                        if ($category['category'] !== "") { ?>
                            <label><input type="checkbox" class="select-category" id="<?=$category['category']?>" value="<?=$category['category']?>"><span class="paragraph"><?=$category['category'] ?: "All categories"?></span>
                                <?php if ($session->is_admin()) {?>
                                    <a href="../pages/edit_category.php?category=<?=$category['category']?>" class="material-symbols-outlined">edit</a>
                                    <form method="post" action="../actions/action_remove_category.php" class="confirmation">
                                        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                                        <button title="Remove category" type="submit" value="<?=$category['category']?>" name="remove-category" class="role confirm-action"><i class="material-symbols-outlined big"> delete </i>
                                        </button>
                                    </form>
                                <?php }?>
                            </label>
                        <?php }} ?>
                </div>
                <p>Order by</p>
                <label for="order"></label>
                <select id="order">
                    <option value="recent">Most recent</option>
                    <option value="price-asc">Ascending Price</option>
                    <option value="price-desc">Descending Price</option>
                </select>
                <p>Price</p>
                <div class="price-input">
                    <label> Minimum
                        <input name="minimum" type="number"
                               class="min-input"
                               value="0">
                    </label>
                    <label> Maximum
                        <input name="maximum" type="number"
                               class="max-input"
                               value="<?=ceil((8500 * Currency::get_currency_conversion($dbh, $session->getCurrency()))/10)*10?>">
                    </label>
                </div>
                <?php
                foreach ($categories as $category) {
                    draw_category_tags($dbh, $category['category']);
                }
                 ?>
            </section>
        <button id="open-filters" onclick="openFilters()"><i class="material-symbols-outlined big filled">filter_list</i></button>
        <section class="items searchresult">
            <div class="loader"></div>
        </section>
        </article>
<?php } ?>

<?php function draw_item_tracking(TrackItem $trackItem, Session $session, Currency $user_currency) { ?>
    <script src="../scripts/print.js" defer></script>
    <form method="get" action="../pages/inbox.php">
        <label>
            <button class="sendMessage-btn" id="contact-seller" type="submit"><?=$trackItem->buyer == $session->getId()? "Contact Seller" : ($trackItem->tracking[0]->creator->user_id == $session->getId()? "Contact buyer" : "")?></button>
        </label>
        <input type="hidden" name="user_id" value="<?=$trackItem->buyer == $session->getId()? $trackItem->tracking[0]->creator->user_id : ($trackItem->tracking[0]->creator->user_id == $session->getId()? $trackItem->buyer : "")?>">
        <input type="hidden" name="item_id" value="<?=$trackItem->tracking[0]->id?>">
    </form>
    <ul class="state">
        <li class="<?= ($trackItem->state == "preparing"? "current" : "done")?>">Preparing</li>
        <li class="<?=($trackItem->state == "shipping"? "current" : (($trackItem->state == "delivering" || $trackItem->state == "delivered") ? "done":""))?>">Shipping</li>
        <li class="<?=($trackItem->state == "delivering"? "current" : ($trackItem->state == "delivered" ? "done":""))?>">Delivering</li>
        <li class="<?=($trackItem->state == "delivered"? "done" : "")?>">Delivered</li>
    </ul>
    <div id="delivery-date">
        <p>Estimated delivery date: </p>
        <?php if ($trackItem->state != "delivered" && $trackItem->tracking[0]->creator->user_id == $session->getId()) {?>
            <form method="post" action="../actions/action_update_delivery.php">
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

                <input value="<?=$trackItem->date?>" id="set_date" name="new-date">
                <input type="hidden" value="<?=$trackItem->id?>" name="purchase">
                <button type="submit">Confirm</button>
            </form>
        <?php }
        else {?>
            <p><?=$trackItem->date?></p>
        <?php } ?>
    </div>
    <?php draw_sliding_items($trackItem->tracking, $user_currency);
    if ($trackItem->tracking[0]->creator->user_id == $session->getId()) { ?>
        <button id="print" class="../pages/sale_info.php?purchase=<?=$trackItem->id?>">Get shipping form</button>
    <?php } ?>
<?php } ?>

<?php function draw_item_to_track(PDO $db, Item $item) { ?>
    <a href="../pages/track_item.php?purchase=<?= Item::get_purchase_id($db, $item->id) ?>" class="item" id="<?=$item->id?>">
        <img src="../uploads/thumbnails/<?=$item->mainImage?>.png" alt="item image">
        <div class="item-info">
            <p class="name"><?=$item->name?></p>
            <p class="price"><?=$item->price?></p>
        </div>
    </a>
<?php } ?>

<?php function draw_sale_info(PDO $db, TrackItem $trackItem, string $seller) { ?>
    <script src="https://unpkg.com/@bitjson/qr-code@1.0.2/dist/qr-code.js"></script>
    <qr-code
            id="qr1"
            contents="http://localhost:9000/pages/confirm_ship.php?purchase=<?=$trackItem->id?>"
            module-color="#000000"
            position-ring-color="#000000"
            position-center-color="#000000"
            mask-x-to-y-ratio="1.2"
            style="
    width: 40%;
    height: 40%;
    margin: 2em auto;
    border: 1rem solid #F2E8D5;
    border-radius: 2rem;
  "
            ><img src="../resources/logo.png" slot="icon" style="width: 100%">
    </qr-code>
    <div class="shipInfo">
        <?php $user = User::get_user($db, $trackItem->buyer)?>
        <p>Addressee: <?=$user->name?></p>
        <p>Email:<?=$user->email?> </p>
        <p>Phone:<?=$user->phone?> </p>
        <p>Destination: <?=$trackItem->address?></p>
        <p>City:<?=$trackItem->city?> </p>
        <p>Postal Code:<?=$trackItem->postalCode?> </p>
    </div>
    <div class="senderInfo">
        <?php $user = User::get_user($db, $seller)?>
        <p>Sender: <?=$user->name?></p>
        <p>Email:<?=$user->email?> </p>
        <p>Phone:<?=$user->phone?> </p>
    </div>
<?php } ?>

<?php function draw_confirm_ship(TrackItem $trackItem) { ?>
    <form action="../actions/action_update_sale.php" method="post">
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

        <input name="confirmationCode" type="password" required>
        <input name="purchase" type="hidden" value="<?=$trackItem->id?>">
        <?php if ($trackItem->state=="preparing" || $trackItem->state=="shipping" || $trackItem->state=="delivering") {
            $text = $trackItem->state=="preparing"? "order was shipped" : ($trackItem->state=="shipping"? "order is being delivered" : "order was delivered");
            ?>

            <button>Confirm <?=$text?> </button>

        <?php }
        ?>

    </form>
<?php } ?>

<?php function draw_new_category_form()
{ ?>
        <script src="../scripts/new_category.js" defer></script>
    <article class="newCategoryPage">
        <h2>New Category</h2>
        <form action="../actions/action_new_category.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
            <label> Category name
                <input type="text" name="category" required>
            </label>
            <i class="material-symbols-outlined add-tag" title="Add tag">add</i>
            <section class="new-tags">
            </section>
            <button type="submit">Submit</button>
        </form>
    </article>
<?php } ?>

<?php function draw_edit_category_form(PDO $dbh, string $category)
{ ?>
    <script src="../scripts/new_category.js" defer></script>
    <article class="newCategoryPage">
        <h2>Edit Category</h2>
        <form action="../actions/action_edit_category.php?id=<?=$category?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

            <label> Category name
                <input type="text" value="<?=$category?>" name="category" required>
            </label>
            <i class="material-symbols-outlined add-tag" title="Add tag">add</i>
            <section class="new-tags">
                <?php $tags = Tag::get_category_tags($dbh, $category);
                for ($i = 0; $i < count($tags); $i++) { ?>
                    <div class="new-tag" id="new-tag-<?=$i?>">
                        <label>Tag name<input type="text" name="tags[<?=$i?>]" value="<?=$tags[$i]['tag']?>" required>
                        </label>
                        <i class="material-symbols-outlined delete-option" title="Delete tag">delete</i>
                        <?php $tag_options = Tag::get_tag_options($dbh, $category, $tags[$i]['tag']);?>
                        <label>
                            <select class="type">
                                <option value="free">Free</option>
                                <option value="select" <?=$tag_options ? "selected" : ""?>>Select</option>
                            </select>
                        </label>
                        <?php
                        if ($tag_options) { ?>
                            <section class="tag-options">
                                 <?php foreach ($tag_options as $key => $tag) {?>
                                    <label class="<?=$i?>">
                                        <input required type="text" name="option<?=$i?>[<?=$key?>]" value="<?=$tag['value']?>">
                                        <i class="material-symbols-outlined remove-option" title="Remove option">close</i>
                                    </label>
                                <?php }?>
                            </section>
                            <i class="material-symbols-outlined new-option" title="Add option">add</i>
                        <?php } ?>
                    </div>
                <?php } ?>

            </section>
            <button type="submit">Submit</button>
        </form>
    </article>
<?php }

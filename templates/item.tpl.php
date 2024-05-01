<?php declare(strict_types = 1);
require_once(__DIR__ . '/../templates/common.tpl.php');

function draw_item(PDO $dbh, Session $session, Item $item) { ?>
    <a href="../pages/item.php?id=<?= $item->id ?>" class="item" id="<?=$item->id?>">
        <img src="../uploads/thumbnails/<?=$item->mainImage?>.png" alt="item photo">
        <div class="item-info">
            <p class="name"><?=$item->name?></p>
            <p class="price"><?=round($item->price * User::get_currency_conversion($dbh, $session->getCurrency()),2) . User::get_currency_symbol($dbh, $session->getCurrency())?></p>
        </div>
    </a>
<?php }

    function draw_items_main( PDO $dbh, Session $session, array $liked_items, array $recent_items) { ?>
    <h2>Last Added Items</h2>
        <a href="../pages/search.php?category=" id="show-more">Show more <i class="material-symbols-outlined">arrow_right_alt </i></a>
        <?php draw_items($dbh, $session, $recent_items); ?>
    <h2>Most liked</h2>
        <?php draw_items($dbh, $session, $liked_items); ?>
    <?php }

    function draw_items(PDO $dbh, Session $session, array $items) { ?>
    <section class="items">
        <?php foreach($items as $item) {
            draw_item($dbh, $session, $item);
        } ?>
    </section>
<?php } ?>

<?php function draw_item_images(array $images) { ?>
    <section class="dots">
    <?php for($i = 0; $i < sizeof($images); $i++) { ?>
        <div class="dot" style="opacity: 0.2;"></div>
    <?php } ?>
    </section>
    <?php foreach($images as $image) { ?>
        <img class="slides" src="../uploads/medium/<?=$image?>.png" alt="item">
    <?php } ?>
<?php } ?>

<?php function draw_item_page(PDO $db, Item $item, Session $session) { ?>
    <article class="itemPage">
        <header>
            <h2><?=$item->name?></h2>
            <?php if ($item->sold === false) { ?>
                <?php if ($session->isLoggedIn() && $item->creator->user_id == $session->getId()) { ?>
                    <form method="post" action="../pages/edit_item.php">
                        <button type="submit" value="<?=$item->id?>" name="edit-item" class="edit" ><i class="material-symbols-outlined big"> edit </i></button>
                    </form>
                <?php } if ($session->isLoggedIn() && $item->creator->user_id !== $session->getId()) { ?>
                    <span class="like"><button value="<?=$item->id?>" class="material-symbols-outlined <?= Item::check_favorite($db, $session->getId(), $item)? "filled": "big"?>"> favorite </button></span> <?php } ?>
            <?php }
            if ($session->isLoggedIn() && (User::get_user($db, $session->getId())->role === "admin" || $session->getId() == $item->creator->user_id)) { ?>
                <form method="post" action="../actions/action_remove_item.php">
                    <button type="submit" value="<?=$item->id?>" name="remove-item" class="edit" ><i class="material-symbols-outlined big"> delete </i></button>
                </form>
            <?php } ?>
        </header>
        <div class="item-images">
            <?php if (count($item->images) > 1) { ?>
                <div class="slider-btns">
                    <i class="material-symbols-outlined" id="prev-btn"> chevron_left </i>
                    <i class="material-symbols-outlined" id="next-btn"> chevron_right </i>
                </div>
            <?php } ?>
            <div class="image-slide">
                <?php draw_item_images($item->images) ?>
            </div>
        </div>
        <script src="../scripts/slide.js" defer></script>
        <section class="description">
            <p><?= $item->description ?></p>
        </section>
        <section class="priceSection">
            <span class="price"><?= round($item->price * User::get_currency_conversion($db, $session->getCurrency()),2) . User::get_currency_symbol($db, $session->getCurrency())?></span>
            <?php if ($item->sold === false) { ?>
                <section class="buy-item">
                    <i class="material-symbols-outlined cart big"> local_mall </i>
                    <button value="<?=$item->id?>" class="Buy"><?=($session->isLoggedIn() && $session->getId() == $item->creator->user_id) ? "You own this product" : (($session->isLoggedIn() && Item::check_cart($db, $session->getId(), $item) || ($session->hasItemsCart() && in_array($item->id, $session->getCart())))?  "Already in cart" : "Buy now!")?></button>
                </section>
            <?php } ?>
        </section>
        <section class="sendMessage">
            <form method="get" action="../pages/inbox.php">
                <label>
                    <button class="sendMessage-btn" type="submit">Send Message</button>
                </label>
                <input type="hidden" name="user_id" value="<?=$item->creator->user_id?>">
                <input type="hidden" name="item_id" value="<?=$item->id?>">
            </form>
        </section>
        <a class="userProfile" href="../pages/user.php?user_id=<?=$item->creator->user_id?>"><?=$item->creator->name?>
            <img src="../uploads/profile_pics/<?=$item->creator->image?>.png" alt="profile picture">
        </a>
        <section class="itemTags">
            <ul>
                <?php if ($item->category) { ?>
                <li>
                    Category: <?=$item->category?>
                </li> <?php } ?>
                <?php foreach($item->tags as $tag) { ?>
                    <li><?= $tag['tag'] . ': ' . $tag['data']?> </li>
                <?php } ?>
                <li>
                    Condition: <?= $item->condition ?>
                </li>
            </ul>
        </section>
    </article>
<?php }

function draw_new_item_form(PDO $db, array $categories) { ?>
    <article class="newItemPage">
        <h2>New item</h2>
        <form action="../actions/action_new_item.php" method="POST" enctype="multipart/form-data">
            <label for="iname">Item Name</label>
            <input type="text" id="iname" name="iname" placeholder="The name of your item" required>

            <label for="category">Category</label>
            <select id="category" name="category">
                <?php foreach ($categories as $category) { ?>
                    <option value="<?=$category['category']?>"><?=ucfirst($category['category'])?: "Other"?></option>
                <?php }?>
            </select>
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
                                <select name="<?=$tag['tag']?>">
                                    <option value=""></option>
                                <?php
                                foreach ($options as $option) { ?>
                                    <option value="<?=$option['value']?>"><?=$option['value']?></option>
                                <?php } ?>
                                </select>
                            </label>
                        <?php }
                        else { ?>
                            <label for="<?=$tag['tag']?>"><?=$tag['tag']?></label><input type="text" id="<?=$tag['tag']?>" name="<?=$tag['tag']?>">
                        <?php }
                    } ?>
                </div>
            <?php }} ?>

            <label for="condition">Condition</label>
            <select id="condition" name="condition">
                <?php $conditions = Tag::get_conditions($db);
                foreach ($conditions as $condition) { ?>
                    <option value="<?=$condition['condition']?>"><?=ucfirst($condition['condition'])?></option>
                <?php }?>
            </select>

            <label for="price">Price</label>
            <input type="number" step="0.01" id="price" name="price" placeholder="The price of your item" required>

            <label for="description">Desciption</label>
            <input type="text" id="description" name="description" placeholder="Describe your item" maxlength="1000" minlength="40">

            <label for="images">images</label>
            <input type="file" id="img1" name="img1" accept="image/*" required>
            <input type="file" id="img2" name="img2" accept="image/*" required>


            <button type="submit" >Submit</button>
        </form>
    </article>
<?php }

function draw_edit_item_form(Item $item) { ?>
    <article class="newItemPage">
        <h2>Edit item</h2>
        <form action="../actions/action_edit_item.php" method="POST" enctype="multipart/form-data">
            <label for="iname">Item Name</label>
            <input type="text" id="iname" name="iname" value="<?= $item->name ?>" required>
            <label for="category">category</label>
            <select id="category" name="category">
                <option value="other" <?=$item->category=="other"? "selected":""?>>Other</option>
                <option value="clothes" <?=$item->category=="clothes"? "selected":""?>>Clothes</option>
                <option value="tech" <?=$item->category=="technology"? "selected":""?>>Technology</option>
                <option value="toys" <?=$item->category=="toys"? "selected":""?>>Toys</option>
                <option value="cars" <?=$item->category=="cars"? "selected":""?>>Cars</option>
                <option value="books" <?=$item->category=="books"? "selected":""?>>Books</option>
                <option value="sports" <?=$item->category=="sports"? "selected":""?>>Sports</option>
            </select>

            <label for="price">Price</label>
            <input type="number" step="0.01" id="price" name="price" value="<?= $item->price ?>" required>

            <label for="description">Desciption</label>
            <input type="text" id="description" name="description" value="<?= $item->description ?>" maxlength="1000" minlength="40">

            <label for="images">images</label>
            <input type="file" id="img1" name="img1" accept="image/*">
            <input type="file" id="img2" name="img2" accept="image/*">

            <button type="submit" value="<?=$item->id?>" name="edit-item">Submit</button>
        </form>
    </article>
<?php }

function draw_page_filters(string $category, PDO $dbh) { ?>
    <script src="../scripts/search.js" defer></script>
    <article class="searchPage">
            <section class="filter">
                <h2>Filters</h2>
                <p>Price</p>
                <div class="price-input">
                    <label> Minimum
                        <input type="number"
                               class="min-input"
                               value="0">
                    </label>
                    <label> Maximum
                        <input type="number"
                               class="max-input"
                               value="8500">
                    </label>
                </div>
                <?php
                $tags = Tag::get_category_tags($dbh, $category);
                foreach ($tags as $tag) { ?>
                    <div class="options tag" id="<?=$tag['tag']?>">
                        <p><?=$tag['tag']?></p>
                        <?php
                        $options = Tag::get_tag_options($dbh, $category, $tag['tag']);
                        if ($options) {
                            foreach ($options as $option) {?>
                            <label><input type="checkbox" name="<?=$tag['tag']?>" value="<?=$option['value']?>" autocomplete='off'><?=$option['value']?></label>
                        <?php }}
                        else { ?>
                            <label><input type="text" name="<?=$tag['tag']?>" autocomplete='off'></label>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="options" id="Condition">
                    <p>Condition</p>
                    <?php $conditions = Tag::get_conditions($dbh);
                    foreach ($conditions as $condition) { ?>
                        <label><input type="checkbox" name="<?=$condition['condition']?>" autocomplete='off'><?=ucfirst($condition['condition'])?></label>
                    <?php }?>
                </div>
            </section>
        <p class="category-search"><?=$category?></p>
        <section class="items searchresult">
        </section>
        </article>
<?php } ?>

<?php function draw_item_tracking(PDO $dbh, TrackItem $trackItem, Session $session) { ?>
    <section class="item-track">
        <button id="contact-seller"><?= $trackItem->buyer == $session->getId()? "Contact Seller" : ($trackItem->tracking[0]->creator->user_id == $session->getId()? "Contact buyer" : "") ?></button>
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
                    <input value="<?=$trackItem->date?>" id="set_date" name="new-date">
                    <input type="hidden" value="<?=$trackItem->id?>" name="purchase">
                    <button type="submit">Confirm</button>
                </form>
            <?php }
            else {?>
                <p><?=$trackItem->date?></p>
            <?php } ?>
        </div>
        <?php draw_items($dbh, $session, $trackItem->tracking );
        if ($trackItem->tracking[0]->creator->user_id == $session->getId()) { ?>
            <button id="print" class="../pages/saleInfo.php?purchase=<?=$trackItem->id?>">Get shipping form</button>
        <?php } ?>
    </section>

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

<?php function draw_sale_info(PDO $db, TrackItem $trackItem, int $seller) { ?>
    <script src="https://unpkg.com/@bitjson/qr-code@1.0.2/dist/qr-code.js"></script>
    <qr-code
            id="qr1"
            contents="http://localhost:9000/pages/confirmShip.php?purchase=<?=$trackItem->id?>"
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

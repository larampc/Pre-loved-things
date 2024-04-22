<?php declare(strict_types = 1);
require_once(__DIR__ . '/../templates/common.tpl.php');

function draw_item(Item $item) { ?>
    <a href="../pages/item.php?id=<?= $item->id ?>" class="item" id="<?=$item->id?>">
        <img src="<?="../images/" . $item->mainImage?>" alt="<?= explode($item->mainImage,'.')[0]?>">
        <div class="item-info">
            <p class="name"><?=$item->name?></p>
            <p class="price"><?=$item->price?></p>
        </div>
    </a>
<?php }

    function draw_items_main(array $items) { ?>
    <h2>Last Added Items</h2>
        <?php draw_items($items); ?>
    <h2>Most liked</h2>
        <?php draw_items($items); ?>
    <?php }

    function draw_items(array $items) { ?>
    <section class="items">
        <?php foreach($items as $item) {
            draw_item($item);
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
        <img class="slides" src="<?="../images/" . $image?>" alt="item">
    <?php } ?>
<?php } ?>

<?php function draw_item_page(PDO $db, Item $item) { ?>
    <article class="itemPage">
        <header>
            <h2><?=$item->name?></h2>
            <?php if ($item->creator == $_SESSION['user_id']) { ?>
                <a class="edit"><i class="material-symbols-outlined big"> edit </i></a>
            <?php } if ($_SESSION['user_id']) { ?>
                <span class="like"><button value="<?=$item->id?>" class="material-symbols-outlined <?= Item::check_favorite($db, (int)$_SESSION['user_id'], $item)? "filled": "big"?>"> favorite </button></span> <?php } ?>
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
            <span class="price"><?= $item->price?></span>
            <section class="buy-item">
                <i class="material-symbols-outlined cart big"> local_mall </i>
                <button value="<?=$item->id?>" class="Buy"><?=Item::check_cart($db, (int)$_SESSION['user_id'], $item) || ($_SESSION['cart'] && in_array($item->id, $_SESSION['cart']))?  "Already in cart" : "Buy now!"?></button>
            </section>
        </section>
        <section class="sendMessage">
            <form>
                <label>
                    <button class="sendMessage-btn" type="submit">Send Message</button>
                </label>
            </form>
        </section>
        <a class="userProfile" href="user.php?user_id=<?=$item->creator?>"><?=get_user($db, $item->creator)['name']?>
            <img src="../images/<?= get_user_image($db, $item->creator)?>" alt="profile picture">
        </a>
        <section class="itemTags">
            <ul>
                <li>
                    Category: <?= $item->category ?>
                </li>
                <li>
                    Size: <?= $item->size ?>
                </li>
                <li>
                    Condition: <?= $item->condition ?>
                </li>
            </ul>
        </section>
    </article>
<?php }

function draw_new_item_form() { ?>
    <article class="newItemPage">
        <h2>New item</h2>
        <form action="../actions/action_new_item.php" method="POST" enctype="multipart/form-data">
            <label for="iname">Item Name</label>
            <input type="text" id="iname" name="iname" placeholder="The name of your item" required>

            <label for="category">category</label>
            <select id="category" name="category">
                <option value="other">Other</option>
                <option value="clothes">Clothes</option>
                <option value="tech">Technology</option>
                <option value="toys">Toys</option>
                <option value="cars">Cars</option>
                <option value="books">Books</option>
                <option value="sports">Sports</option>
            </select>

            <label for="price">Price</label>
            <input type="number" id="price" name="price" placeholder="The price of your item" required>

            <label for="description">Desciption</label>
            <input type="text" id="description" name="description" placeholder="Describe your item" maxlength="1000" minlength="40">

            <label for="images">images</label>
            <input type="file" id="img1" name="img1" accept="image/*" required>
            <input type="file" id="img2" name="img2" accept="image/*" required>


            <input type="submit" value="Submit">
        </form>
    </article>
<?php }

function draw_page_filters(array $items) {
    ?>
    <article class="searchPage">
            <section class="filter">
                <h2>Filters</h2>
                <div class="options" id="Price">
                    <p>Prices</p>
                    <label><input type="checkbox" name="0-5" autocomplete='off'>0-5€</label>
                    <label><input type="checkbox" name="5-10" autocomplete='off'>5-10€</label>
                    <label><input type="checkbox" name="10-20" autocomplete='off'>10-20€</label>
                    <label><input type="checkbox" name="20-50" autocomplete='off'>20-50€</label>
                    <label><input type="checkbox" name="50-9999999" autocomplete='off'>50€</label>
                </div>
                <div class="options" id="Condition">
                    <p>Condition</p>
                    <label><input type="checkbox" name="New" autocomplete='off'>New</label>
                    <label><input type="checkbox" name="Used" autocomplete='off'>Used</label>
                    <label><input type="checkbox" name="Old" autocomplete='off'>Old</label>
                </div>
            </section>
        <section id="searchres">
            <?php draw_items($items); ?>
        </section>

        </article>
<?php } ?>

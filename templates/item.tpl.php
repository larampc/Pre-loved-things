<?php declare(strict_types = 1);
require_once('templates/common.tpl.php');

function draw_item(Item $item) { ?>
    <a href="item.php?id=<?= $item->id ?>" class="item-main">
        <img src="<?="images/" . $item->get_main_image()?>" alt="<?= explode($item->get_main_image(),'.')[0]?>">
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
        <img class="slides" src="<?="images/" . $image?>" alt="item"> 
    <?php } ?>
<?php } ?>

<?php function draw_item_page(Item $item) { ?>
    <article class="itemPage">
        <header>
            <h2><?=$item->name?></h2>
            <?php if ($item->user == $_SESSION["username"]) { ?>
                <span class="edit"><i class="material-symbols-outlined big"> edit </i></span>
            <?php } ?>
            <span class="like"><i class="material-symbols-outlined big"> favorite </i></span>
        </header>
        <div class="item-images">
            <?php if (count($item->images) > 1) { ?>
                <div class="slider-btns">
                    <span class="material-symbols-outlined"> chevron_left </span>
                    <span class="material-symbols-outlined"> chevron_right </span>
                </div>
            <?php } ?>
            <div class="image-slide">
                <?php draw_item_images($item->images) ?>
            </div>
        </div>
        <script src="slide.js"></script>
        <section class="description">
            <p><?= $item->description ?></p>
        </section>
        <section class="priceSection">
            <span class="price"><?= $item->price?></span>
            <form class="buy-item">
                <i class="material-symbols-outlined cart big"> local_mall </i>
                <label>
                    <button class="Buy" type="submit">Buy now!</button>
                </label>
            </form>
        </section>
        <section class="sendMessage">
            <form>
                <label>
                    <button class="sendMessage-btn" type="submit">Send Message</button>
                </label>
            </form>
        </section>
        <section class="userProfile">
            <form>
                <label>
                    <button class="userProfile-btn" type="submit">User Profile</button>
                </label>
            </form>
            <img src="images/profile.png" alt="profile picture">
        </section>
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
        <form action="action_new_item.php" method="POST" enctype="multipart/form-data">
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

            <label for="description">images</label>
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

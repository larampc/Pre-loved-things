<?php declare(strict_types = 1);
require_once('templates/common.tpl.php');

function draw_item($item) { ?>
    <a href="item.php?id=<?= $item['id']?>" class="item-main">
        <img src="<?="images/" . $item['imagePath']?>" alt="<?= explode($item['imagePath'],'.')[0]?>">
        <div class="item-info">
            <p class="name"><?=$item['name']?></p>
            <p class="price"><?=$item['price']?></p>
        </div>
    </a>
<?php }

function draw_items(array $items) { ?>
    <h2>Last Added Items</h2>
    <section class="items">
        <?php foreach($items as $item) {
            draw_item($item);
        } ?>
    </section>
    <h2>Most liked</h2>
    <section class="items">
    <?php foreach($items as $item) {
        draw_item($item);
    } ?>
    </section>
<?php } ?>

<?php function draw_item_page($item) { ?>
    <article class="itemPage">
        <header>
            <h2><?=$item['name']?></h2>
            <span class="edit"><img src="images/pencil.png" alt="pencil"></span>
            <span class="like"><img src="images/heart.png" alt="heart"></span>
        </header>
        <span class="picture"><img src="<?="images/" . $item['imagePath']?>" alt="<?= explode($item['imagePath'],'.')[0]?>"></span>
        <section class="description">
            <p><?= $item['description'] ?></p>
        </section>
        <section class="priceSection">
            <span class="price"><?= $item['price']?></span>
            <form class="buy-item">
                <img src="images/cart.png" alt="cart">
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
                    Category: <?= $item['category'] ?>
                </li>
                <li>
                    Size: <?= $item['size'] ?>
                </li>
                <li>
                    Condition: <?= $item['condition'] ?>
                </li>
            </ul>
        </section>
    </article>
<?php }

function draw_new_item_form() { ?>
    <article class="newItemPage">
        <h2>New item</h2>
        <form action="action_new_item.php" method="POST">
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
<?php } ?>

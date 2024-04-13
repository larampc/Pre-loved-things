<?php

function drawItem($item) { ?>
    <div class="item-main">
        <img src="<?="images/" . $item['imagePath']?>" alt="<?= explode($item['imagePath'],'.')[0]?>">
        <div class="item-info">
            <p class="name"><?=$item['name']?></p>
            <p class="price"><?=$item['price']?></p>
        </div>
    </div>
<?php }

function drawItems(array $items) { ?>
    <h2>Last Added Items</h2>
    <section class="items">
        <?php foreach($items as $item) {
            drawItem($item);
        } ?>
    </section>
    <h2>Most liked</h2>
    <section class="items">
    <?php foreach($items as $item) {
        drawItem($item);
    } ?>
    </section> 
<?php } ?>

<?php function drawItemPage($item) { ?>
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
<?php } ?>
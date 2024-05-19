<?php
declare(strict_types=1);

function draw_cart(array $items, Currency $user_currency) { ?>
    <script src="../scripts/remove_cart.js" defer></script>
    <h2>Your cart</h2>
    <?php
    $user = null;
    $num_items = 0;
    $sum = 0;
    if (empty($items)) { ?>
        <p>You have no items</p>
        <?php
        return;
    }
foreach ($items as $item) {
    if ($user != $item->creator && $user != null) { ?>
        </div>
        <section class="sum">
            <h4 class="num-items">Number items: <?=$num_items?></h4>
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
        </section>
        </div>
        <?php
        $num_items = 0;
        $sum = 0;
    }
if ($user != $item->creator) { ?>
    <div class="seller">
        <a href="../pages/user.php?user_id=<?=$item->creator->user_id?>" class="seller-info">
            <img src="../uploads/profile_pics/<?= $item->creator->image?>.png" class="profile-picture" alt="profile-photo">
            <p><?=$item->creator->name?></p>
        </a>
        <div class="seller-items">
            <?php }
            draw_item($item, $user_currency);
            $num_items += 1;
            $sum += round($item->price * $user_currency->conversion, 2);
            $user = $item->creator;
            } ?>
        </div>
        <section class="sum">
            <h4 class="num-items">Number items: <?=$num_items?></h4>
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
        </section>
    </div>
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
                        <input type="text" name="postalCode" required autocomplete="on"
                               pattern="\d{4}-\d{3}"
                               oninvalid="this.setCustomValidity('Invalid postal code')"
                               oninput="this.setCustomValidity('')">
                    </label>
                    <button type="button" class="next shipping">Next</button>
                </div>
            </li>
            <li>
                <button type="button" class="collapsible">Payment information</button>
                <div class="buy-form">
                    <div class="options">
                        <label> Credit card
                            <input class="option" type="radio" name="credit-card" checked>
                        </label>
                        <label> Mbway
                            <input class="option" type="radio" name="mbway">
                        </label>
                    </div>
                    <div id="credit-card" class="payment-form">
                        <label> Card number
                            <input type="text" name="card-number" required
                                   pattern="\d{16}"
                                   oninvalid="this.setCustomValidity('Invalid card number')"
                                   oninput="this.setCustomValidity('')">
                        </label>
                        <label> CVC
                            <input type="text" name="cvc" required
                                   pattern="\d{3}"
                                   oninvalid="this.setCustomValidity('Invalid CVC')"
                                   oninput="this.setCustomValidity('')">
                        </label>
                        <label> Expiration date
                            <input type="month" name="expire" min="<?=date("Y-m", time())?>" required>
                        </label>
                    </div>
                    <div id="mbway" class="payment-form">
                        <label> Phone number
                            <input type="text" name="phone" autocomplete="on"
                                   pattern="\d{9}|\d{3}-\d{3}-\d{3}"
                                   oninvalid="this.setCustomValidity('Invalid phone number - (ddddddddd or ddd-ddd-ddd)')"
                                   oninput="this.setCustomValidity('')">
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

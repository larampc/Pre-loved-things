<?php
    declare(strict_types = 1);

    session_start();

    require_once('database/connection.db.php');

    require_once('templates/common.tpl.php');

    draw_header("cart");

    ?>

<article class="cartPage">
        <h2>Your cart</h2>
        <section class="seller">
            <div class="seller-info">
                <img src="images/profile.png" class="profile-pic" alt="profile-photo">
                <p>Username</p>
            </div>
            <section class="seller-items">
                <div class="item">
                    <img src="images/flower.png" alt="item">
                    <div class="item-info">
                        <p class="name">Name</p>
                        <p class="price">Price</p>
                    </div>
                </div>
            </section>
            <div class="sum">
                <p>Number items</p>
                <div class="sum-price">
                    <p>Total: </p>
                    <p>Price</p>
                </div>
                <form class="buy-item">
                    <label>
                        <button class="Buy" type="submit">Buy now!</button>
                    </label>
                </form>
            </div>
        </section>
        <section class="seller">
            <div class="seller-info">
                <img src="images/profile.png" class="profile-pic" alt="profile">
                <p>Username</p>
            </div>
            <section class="seller-items">
                <div class="item">
                    <img src="images/flower.png" alt="item">
                    <div class="item-info">
                        <p class="name">Name</p>
                        <p class="price">Price</p>
                    </div>
                </div>
                <div class="item">
                    <img src="images/flower.png" alt="item">
                    <div class="item-info">
                        <p class="name">Name</p>
                        <p class="price">Price</p>
                    </div>
                </div>
                <div class="item">
                    <img src="images/flower.png"  alt="item">
                    <div class="item-info">
                        <p class="name">Name</p>
                        <p class="price">Price</p>
                    </div>
                </div>
                <div class="item">
                    <img src="images/flower.png" alt="item">
                    <div class="item-info">
                        <p class="name">Name</p>
                        <p class="price">Price</p>
                    </div>
                </div>
            </section>
            <div class="sum">
                <p>Numer items</p>
                <div class="sum-price">
                    <p>Total: </p>
                    <p>Price</p>
                </div>
                <form class="buy-item">
                    <label>
                        <button class="Buy" type="submit">Buy now!</button>
                    </label>
                </form>
            </div>
        </section>
        <section class="seller">
            <div class="seller-info">
                <img src="images/profile.png" class="profile-pic" alt="profile">
                <p>Username</p>
            </div>
            <section class="seller-items">
                <div class="item">
                    <img src="images/flower.png" alt="item">
                    <div class="item-info">
                        <p class="name">Name</p>
                        <p class="price">Price</p>
                    </div>
                </div>
                <div class="item">
                    <img src="images/flower.png" alt="item">
                    <div class="item-info">
                        <p class="name">Name</p>
                        <p class="price">Price</p>
                    </div>
                </div>
                <div class="item">
                    <img src="images/flower.png" alt="item">
                    <div class="item-info">
                        <p class="name">Name</p>
                        <p class="price">Price</p>
                    </div>
                </div>
            </section>
            <div class="sum">
                <p>Numer items</p>
                <div class="sum-price">
                    <p>Total: </p>
                    <p>Price</p>
                </div>
                <form class="buy-item">
                    <label>
                        <button class="Buy" type="submit">Buy now!</button>
                    </label>
                </form>
            </div>
        </section>
    </article>

<?php
    draw_footer();



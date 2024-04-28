<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/item.class.php');
    require_once(__DIR__ . '/../database/track_item.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/tags.class.php');
    require_once(__DIR__ . '/../database/comment.class.php');

    require_once(__DIR__ . '/../templates/user.tpl.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/item.tpl.php');

    $dbh = get_database_connection();
    $categories = Tag::get_categories($dbh);
    draw_header("user", $session, $categories);

    $user_id = intval($_GET['user_id']);
    $user = User::get_user($dbh, $user_id);
    $feedback = User::get_user_feedback($dbh, $user_id);
    $items = Item::get_user_items($dbh, $user_id);

    draw_user_profile($dbh, $user, $feedback, $items, $session);
    draw_footer(); ?>

<div class="background"></div>
<div class="background-texture"></div>

<section class="carousel">
    <h2 class="categories__title">My List</h2>
    <div class="carousel__container">
        <div class="carousel-item">
            <img
                    class="carousel-item__img"
                    src="https://images.pexels.com/photos/708392/pexels-photo-708392.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                    alt="people"
            />
            <div class="carousel-item__details">
                <div class="controls">
                    <span class="fas fa-play-circle"></span>
                    <span class="fas fa-plus-circle"></span>
                </div>
                <h5 class="carousel-item__details--title">Descriptive Title</h5>
                <h6 class="carousel-item__details--subtitle">Date and Duration</h6>
            </div>
        </div>
        <div class="carousel-item">
            <img
                    class="carousel-item__img"
                    src="https://images.pexels.com/photos/1785001/pexels-photo-1785001.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                    alt="people"
            />
            <div class="carousel-item__details">
                <div class="controls">
                    <span class="fas fa-play-circle"></span>
                    <span class="fas fa-plus-circle"></span>
                </div>
                <h5 class="carousel-item__details--title">Descriptive Title</h5>
                <h6 class="carousel-item__details--subtitle">Date and Duration</h6>
            </div>
        </div>
        <div class="carousel-item">
            <img
                    class="carousel-item__img"
                    src="https://images.pexels.com/photos/417344/pexels-photo-417344.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                    alt="people"
            />
            <div class="carousel-item__details">
                <div class="controls">
                    <span class="fas fa-play-circle"></span>
                    <span class="fas fa-plus-circle"></span>
                </div>
                <h5 class="carousel-item__details--title">Descriptive Title</h5>
                <h6 class="carousel-item__details--subtitle">Date and Duration</h6>
            </div>
        </div>
        <div class="carousel-item">
            <img
                    class="carousel-item__img"
                    src="https://images.pexels.com/photos/1071882/pexels-photo-1071882.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                    alt="people"
            />
            <div class="carousel-item__details">
                <div class="controls">
                    <span class="fas fa-play-circle"></span>
                    <span class="fas fa-plus-circle"></span>
                </div>
                <h5 class="carousel-item__details--title">Descriptive Title</h5>
                <h6 class="carousel-item__details--subtitle">Date and Duration</h6>
            </div>
        </div>
        <div class="carousel-item">
            <img
                    class="carousel-item__img"
                    src="https://images.pexels.com/photos/417344/pexels-photo-417344.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                    alt="people"
            />
            <div class="carousel-item__details">
                <div class="controls">
                    <span class="fas fa-play-circle"></span>
                    <span class="fas fa-plus-circle"></span>
                </div>
                <h5 class="carousel-item__details--title">Descriptive Title</h5>
                <h6 class="carousel-item__details--subtitle">Date and Duration</h6>
            </div>
        </div>
        <div class="carousel-item">
            <img
                    class="carousel-item__img"
                    src="https://images.pexels.com/photos/6945/sunset-summer-golden-hour-paul-filitchkin.jpg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
                    alt="people"
            />
            <div class="carousel-item__details">
                <div class="controls">
                    <span class="fas fa-play-circle"></span>
                    <span class="fas fa-plus-circle"></span>
                </div>
                <h5 class="carousel-item__details--title">Descriptive Title</h5>
                <h6 class="carousel-item__details--subtitle">Date and Duration</h6>
            </div>
        </div>

        <div class="carousel-item">
            <img
                    class="carousel-item__img"
                    src="https://images.pexels.com/photos/1964471/pexels-photo-1964471.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260"
                    alt="people"
            />
            <div class="carousel-item__details">
                <div class="controls">
                    <span class="fas fa-play-circle"></span>
                    <span class="fas fa-plus-circle"></span>
                </div>
                <h5 class="carousel-item__details--title">Descriptive Title</h5>
                <h6 class="carousel-item__details--subtitle">Date and Duration</h6>
            </div>
        </div>
    </div>
</section>

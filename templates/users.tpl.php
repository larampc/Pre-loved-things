<?php

declare(strict_types=1);
require_once(__DIR__ . '/../templates/common.tpl.php');
?>

<?php function draw_register_form(bool $checkout, bool $message, string $user, string $item)
{ ?>

    <section class="register">
        <h2>Sign Up</h2>
        <form class="register-form" action="../actions/action_register.php<?=$checkout? "?checkout": ($message? "?user=".$user."&item=".$item:"")?>" method="POST">
            <label>
                <input type="text" name="name" placeholder="Your name" required autocomplete="on">
            </label>
            <label>
                <input type="text" name="username" placeholder="Your username" required autocomplete="on">
            </label>
            <label>
                <input type="email" name="email" placeholder="Your email" required autocomplete="on">
            </label>
            <label>
                <input type="text" name="phone" placeholder="Your phone number" required autocomplete="on">
            </label>
            <label>
                <input type="password" name="password" placeholder="Your password" autocomplete="off" required>
            </label>
            <button class="login-button" type="submit">Register</button>
            <p class="rotateRegister">Already have an account?</p>
        </form>
    </section>

<?php } ?>

<?php function draw_login_form(bool $checkout, bool $message, string $user, string $item)
{ ?>
    <section class="login">
        <h2>Log in</h2>
        <form class="login-form" action="../actions/action_login.php<?=$checkout? "?checkout": ($message? "?user=".$user."&item=".$item:"")?>" method="POST">
            <label>
                <input type="text" name="email" placeholder="Your email or username" autocomplete="on">
            </label>
            <label>
                <input type="password" name="password" placeholder="Your password" autocomplete="off">
            </label>
            <button class="login-button" type="submit">Submit</button>
            <a href="#" class="forgot-password">Forgot your password?</a>
            <p class="rotateLogin">Don't have an account?</p>
        </form>
    </section>

<?php } ?>

<?php function draw_login_register_form(bool $checkout, bool $message, string $user, string $item)
{ ?>

    <section class="flipLoginRegister">
        <?php draw_login_form($checkout, $message, $user, $item); ?>
        <?php draw_register_form($checkout, $message, $user, $item); ?>
    </section>
    <h3 class="current-page">
        <span>Log in</span>
        <span>Sign up</span>
    </h3>
    <label for="checkboxLoginRegister"></label><input class="checkboxLoginRegister" id="checkboxLoginRegister" type="checkbox">
    <span class="toggleLoginRegister"></span>

<?php } ?>

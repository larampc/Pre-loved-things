<?php

declare(strict_types=1);
require_once(__DIR__ . '/../templates/common.tpl.php');
?>

<?php function draw_register_form(bool $checkout)
{ ?>

    <section class="register">
        <h2>Sign Up</h2>
        <form class="register-form" action="../actions/action_register.php<?=$checkout? "?checkout": ""?>" method="POST">
            <input type="text" name="name" placeholder="Your name">
            <i class="input-icon"></i>
            <input type="email" name="email" placeholder="Your email">
            <i class="input-icon"></i>
            <input type="text" name="phone" placeholder="Your phone number">
            <i class="input-icon"></i>
            <input type="password" name="password" placeholder="Your password" autocomplete="off">
            <i class="input-icon"></i>
            <button class="login-button" type="submit">Register</button>
        </form>
    </section>

<?php } ?>

<?php function draw_login_form(bool $checkout)
{ ?>
    <section class="login">
        <h2>Log in</h2>
        <form class="login-form" action="../actions/action_login.php<?=$checkout? "?checkout": ""?>" method="POST">
            <input type="email" name="email" placeholder="Your email">
            <i class="input-icon"></i>
            <input type="password" name="password" placeholder="Your password" autocomplete="off">
            <i class="input-icon"></i>
            <button class="login-button" type="submit">Submit</button>
            <a href="#" class="forgot-password">Forgot your password?</a>
            <a href="../pages/register.php<?=$checkout? "?checkout": ""?>" class="forgot-password">Don't have an account?</a>
        </form>
    </section>

<?php } ?>
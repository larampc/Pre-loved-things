<?php

declare(strict_types=1);
require_once('templates/common.tpl.php');
?>

<?php function draw_register_form()
{ ?>

    <section class="register">
        <h2>Sign Up</h2>
        <form class="register-form" action="action_register.php" method="POST">
            <label>
                <input type="text" name="username" placeholder="Your name">
            </label>
            <i class="input-icon"></i>
            <label>
                <input type="email" name="email" placeholder="Your email">
            </label>
            <i class="input-icon"></i>
            <label>
                <input type="password" name="password" placeholder="Your password" autocomplete="off">
            </label>
            <i class="input-icon"></i>
            <button class="login-button" type="submit">Register</button>
        </form>
    </section>

<?php } ?>

<?php function draw_login_form()
{ ?>

    <section class="login">
        <h2>Log in</h2>
        <form class="login-form" action="action_login.php" method="POST">
            <label>
                <input type="text" name="username" placeholder="Your username">
            </label>
            <i class="input-icon"></i>
            <label>
                <input type="password" name="password" placeholder="Your password" autocomplete="off">
            </label>
            <i class="input-icon"></i>
            <button class="login-button" type="submit">Submit</button>
            <a href="#" class="forgot-password">Forgot your password?</a>
            <a href="register.php" class="forgot-password">Don't have an account?</a>
        </form>
    </section>

<?php } ?>
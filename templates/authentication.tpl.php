<?php

declare(strict_types=1);
?>

<?php function draw_register_form(bool $checkout, bool $message, string $user, string $item)
{ ?>
    <section class="register">
        <h2>Sign Up</h2>
        <form class="register-form" action="../actions/action_register.php<?=$checkout? "?checkout": ($message? "?user=".$user."&item=".$item:"")?>" method="POST">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

            <label>
                <input type="text" name="name" placeholder="Your name" required
                       pattern=".{1,50}" oninvalid="this.setCustomValidity('Invalid name - can not be longer than 50 chars')"
                       oninput="this.setCustomValidity('')" autocomplete="on">
            </label>
            <label>
                <input type="text" name="username" placeholder="Your username" required
                       pattern="\w{1,30}" oninvalid="this.setCustomValidity('Invalid username - can only contain letters, digits and underscores and can not be longer than 30 chars')"
                       oninput="this.setCustomValidity('')" autocomplete="on">
            </label>
            <label>
                <input type="email" name="email" placeholder="Your email" required autocomplete="on">
            </label>
            <label>
                <input type="tel" name="phone" placeholder="Your phone number" required
                       pattern="\d{9}|\d{3}-\d{3}-\d{3}"
                       oninvalid="this.setCustomValidity('Invalid phone number - (ddddddddd or ddd-ddd-ddd)')"
                       oninput="this.setCustomValidity('')" autocomplete="on">
            </label>
            <label>
                <input type="password" name="password" placeholder="Your password" autocomplete="off"
                       pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[.?!@#$%&<>\(\)\[\]\{\}\/\\\-\|+~\^\*]).{8,}"
                       oninvalid="this.setCustomValidity('Invalid password - must contain at least one uppercase letter, one lowercase letter, one digit, one special character, and be at least 8 characters long')"
                       oninput="this.setCustomValidity('')"
                       required>
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
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

            <label>
                <input type="text" name="email" placeholder="Your email or username" autocomplete="on">
            </label>
            <label>
                <input type="password" name="password" placeholder="Your password" autocomplete="off">
            </label>
            <button class="login-button" type="submit">Submit</button>
        </form>
        <script src="../scripts/forgot_password.js" defer></script>
        <p class="forgot-password">Forgot your password?</p>
        <dialog class="forgot-popup">
            <form method="post" action="../actions/action_forgot_password.php">
                <input type="email" name="email" placeholder="Please enter your email" required>
                <button type="submit">Send recovery email</button>
            </form>
        </dialog>
        <p class="rotateLogin">Don't have an account?</p>
    </section>
<?php } ?>

<?php function draw_login_register_form(bool $checkout, bool $message, string $user, string $item)
{ ?>
    <script src="../scripts/flip.js" defer></script>
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

<?php function draw_recover_password()
{ ?>
    <form class="login-form" action="../actions/action_recover_password.php" method="POST">
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">

        <label>
            <input type="text" name="email" placeholder="Your email or username" autocomplete="on" required>
        </label>
        <label>
            <input type="password" name="code" placeholder="The code send to email" autocomplete="off" required>
        </label>
        <label>
            <input type="password" name="password" placeholder="Your password" autocomplete="off"
                   pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[?!@#$%&<>\(\)\[\]\{\}\/\\\-\|+~\^\*]).{8,}"
                   oninvalid="this.setCustomValidity('Invalid password - must contain at least one uppercase letter, one lowercase letter, one digit, one special character, and be at least 8 characters long')"
                   oninput="this.setCustomValidity('')"
                   required>
        </label>
        <button class="login-button" type="submit">Submit</button>
    </form>

<?php } ?>

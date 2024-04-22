<?php

declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../templates/users.tpl.php');

draw_header("login");
draw_login_form();
draw_footer();
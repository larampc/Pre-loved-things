<?php

declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../templates/users.tpl.php');

draw_header("register");
draw_register_form();
draw_footer();
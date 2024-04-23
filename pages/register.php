<?php

declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../templates/users.tpl.php');

$checkout = isset($_GET['checkout']);

draw_header("register");
draw_register_form($checkout);
draw_footer();
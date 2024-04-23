<?php

declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../templates/users.tpl.php');

$checkout = isset($_GET['checkout']);
if ($checkout) $_SESSION['user_items'] = $_POST['user_items'];

draw_header("login");
draw_login_form($checkout);
draw_footer();
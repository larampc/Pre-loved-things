<?php
declare(strict_types = 1);

session_start();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');

require_once(__DIR__ . '/../templates/user.tpl.php');
require_once(__DIR__ . '/../templates/item.tpl.php');

$db = get_database_connection();

$user_items = intval($_SESSION['user_items']? : $_POST['user_items']);
$items = User::get_cart_items_from_user($db, $_SESSION['user_id'], $user_items);

draw_header("cart-checkout");
draw_checkout_form();
draw_checkout_summary($items);
draw_footer();

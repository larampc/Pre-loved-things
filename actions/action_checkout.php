<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if(!validate_csrf_token($_POST['csrf'])) {
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

$session->set_item_checkout($_POST['user_items']);

Header("Location: ". ($session-> is_logged_in()?"../pages/checkout.php": "../pages/login.php?checkout"));

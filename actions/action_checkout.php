<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

$session->setItemCheckout($_POST['user_items']);

Header("Location: ". ($session->isLoggedIn()?"../pages/checkout.php": "../pages/login.php?checkout"));

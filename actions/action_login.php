<?php

declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$user = User::verify_user($dbh, $_POST['email'], $_POST['password']);
$checkout = isset($_GET['checkout']);
if ($user != -1) {
  $_SESSION['user_id'] = $user;
  if ($_SESSION['cart']) User::add_to_cart($dbh, $_SESSION['cart'], $user);
  header('Location: '. ($checkout? '../pages/checkout.php':'../index.php'));
} else  header('Location: ../pages/login.php'. ($checkout? '?checkout':''));
